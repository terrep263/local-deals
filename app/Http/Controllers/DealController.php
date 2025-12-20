<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DealController extends Controller
{
    /**
     * Browse all deals with filters
     */
    public function index(Request $request)
    {
        $query = Deal::with(['vendor', 'category'])->active();
        
        // Category filter
        if ($request->filled('category')) {
            $categoryIds = is_array($request->category) ? $request->category : [$request->category];
            $query->whereIn('category_id', $categoryIds);
        }
        
        // Location filter
        if ($request->filled('city')) {
            $query->where('location_city', $request->city);
        }
        
        if ($request->filled('zip')) {
            $query->where('location_zip', $request->zip);
        }
        
        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('deal_price', '>=', $request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('deal_price', '<=', $request->price_max);
        }
        
        // Discount filter
        if ($request->filled('discount')) {
            $query->where('discount_percentage', '>=', $request->discount);
        }
        
        // Availability filter
        if ($request->filled('availability')) {
            if ($request->availability === 'high') {
                $query->whereRaw('inventory_remaining > (inventory_total * 0.5)');
            } elseif ($request->availability === 'low') {
                $query->whereRaw('inventory_remaining <= 10 AND inventory_remaining > 0');
            }
        }
        
        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'ending_soon':
                $query->orderBy('expires_at', 'asc');
                break;
            case 'best_discount':
                $query->orderBy('discount_percentage', 'desc');
                break;
            case 'price_low':
                $query->orderBy('deal_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('deal_price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $deals = $query->paginate(24);
        
        // Get filter data
        $categories = \App\Models\Categories::orderBy('category_name')->get();
        $cities = [
            'Fruitland Park', 'Lady Lake', 'Leesburg', 'The Villages', 'Tavares',
            'Mount Dora', 'Eustis', 'Umatilla', 'Clermont', 'Minneola',
            'Groveland', 'Mascotte', 'Montverde', 'Howey-in-the-Hills', 'Astatula', 'Okahumpka',
        ];
        
        return view('deals.index', compact('deals', 'categories', 'cities'));
    }

    /**
     * Show deal detail page
     */
    public function show($slug)
    {
        $deal = Deal::where('slug', $slug)
            ->whereIn('status', ['active', 'paused']) // Allow paused deals to be viewed
            ->with(['vendor', 'category'])
            ->firstOrFail();
        
        // Increment view count
        $deal->incrementViewCount();
        
        // Calculate inventory status
        $inventoryRemaining = $deal->inventory_remaining;
        $inventoryPercent = $deal->inventory_total > 0 ? ($inventoryRemaining / $deal->inventory_total) * 100 : 0;
        
        // Days until expiry
        $daysUntilExpiry = $deal->getDaysUntilExpiry();
        
        return view('deals.show', compact('deal', 'inventoryRemaining', 'inventoryPercent', 'daysUntilExpiry'));
    }

    /**
     * Track click on Buy Now button
     */
    public function trackClick(Request $request, $slug)
    {
        $deal = Deal::where('slug', $slug)->firstOrFail();
        $deal->incrementClickCount();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Show purchase claim form
     */
    public function claimPurchase($slug)
    {
        $deal = Deal::where('slug', $slug)
            ->whereIn('status', ['active', 'paused'])
            ->with(['vendor', 'category'])
            ->firstOrFail();
        
        if ($deal->is_sold_out) {
            \Session::flash('error_flash_message', 'This deal is sold out.');
            return redirect()->route('deals.show', $deal->slug);
        }
        
        return view('deals.claim-purchase', compact('deal'));
    }
    
    /**
     * Process purchase claim
     */
    public function processClaim(Request $request, $slug)
    {
        $request->validate([
            'email' => 'required|email',
            'confirmed' => 'required|accepted',
        ]);
        
        $deal = Deal::where('slug', $slug)
            ->whereIn('status', ['active', 'paused'])
            ->firstOrFail();
        
        // Check if sold out
        if ($deal->is_sold_out) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'This deal is sold out.']);
        }
        
        // Rate limiting: max 3 attempts per email per deal
        $key = 'claim-purchase:' . $deal->id . ':' . $request->email;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Too many claim attempts. Please contact support.']);
        }
        
        RateLimiter::hit($key, 3600); // 1 hour window
        
        try {
            // Generate confirmation code
            $confirmationCode = strtoupper(Str::random(8));
            
            // Ensure uniqueness
            while (DealPurchase::where('confirmation_code', $confirmationCode)->exists()) {
                $confirmationCode = strtoupper(Str::random(8));
            }
            
            // Create purchase record
            $purchase = DealPurchase::create([
                'deal_id' => $deal->id,
                'consumer_email' => $request->email,
                'consumer_name' => $request->name ?? null,
                'purchase_amount' => $deal->deal_price,
                'confirmation_code' => $confirmationCode,
                'purchase_date' => now(),
                'vendor_notified' => false,
            ]);
            
            // Increment inventory (this auto-closes if sold out)
            $deal->incrementInventorySold();

            // Increment vendor voucher usage and auto-pause if capacity reached
            $vendorProfile = $deal->vendor?->vendorProfile;
            if ($vendorProfile) {
                $vendorProfile->incrementVoucherUsage();
            }
            
            // Calculate and record commission
            try {
                $commissionService = app(\App\Services\CommissionService::class);
                $commission = $commissionService->calculateCommission($purchase);
                
                // Check if vendor hit volume limit
                $vendor = $deal->vendor;
                $currentCount = $commissionService->getCurrentMonthVoucherCount($vendor->id);
                $subscription = $vendor->activeSubscription;
                $packageFeatures = $subscription 
                    ? $subscription->packageFeature() 
                    : \App\Models\PackageFeature::getByTier('starter');
                
                if ($packageFeatures && $packageFeatures->monthly_voucher_limit && $currentCount >= $packageFeatures->monthly_voucher_limit) {
                    // Pause vendor's deals
                    $vendor->deals()->where('status', 'active')->update(['status' => 'paused_limit']);
                    
                    // Send notification (will create notification class later)
                    \Log::warning('Vendor hit voucher limit', [
                        'vendor_id' => $vendor->id,
                        'current_count' => $currentCount,
                        'limit' => $packageFeatures->monthly_voucher_limit,
                    ]);
                }
                
                // Check for upgrade opportunities
                $upgradeService = app(\App\Services\UpgradeDetectionService::class);
                $upgradeService->checkForUpgradeOpportunities($vendor->id);
            } catch (\Exception $e) {
                \Log::error('Commission calculation failed: ' . $e->getMessage());
                // Don't fail the purchase if commission calculation fails
            }
            
            // Send confirmation email to consumer
            try {
                Mail::send('emails.deal_purchase_confirmation', [
                    'purchase' => $purchase,
                    'deal' => $deal,
                ], function ($message) use ($request, $deal) {
                    $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                    $message->to($request->email)->subject('Your Deal Voucher - ' . $deal->title);
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send purchase confirmation email: ' . $e->getMessage());
            }
            
            // Send notification to vendor
            try {
                Mail::send('emails.vendor_deal_purchase_notification', [
                    'purchase' => $purchase,
                    'deal' => $deal,
                ], function ($message) use ($deal) {
                    $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                    $message->to($deal->vendor->email)->subject('New Purchase: ' . $deal->title);
                });
                
                $purchase->update(['vendor_notified' => true]);
            } catch (\Exception $e) {
                \Log::error('Failed to send vendor notification email: ' . $e->getMessage());
            }
            
            \Session::flash('flash_message', 'Purchase confirmed! Check your email for your voucher.');
            return redirect()->route('vouchers.show', $confirmationCode);
            
        } catch (\Exception $e) {
            \Log::error('Purchase claim failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to process claim. Please try again or contact support.']);
        }
    }
}

