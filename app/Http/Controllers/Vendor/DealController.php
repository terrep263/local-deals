<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Categories;
use App\Models\DealAIAnalysis;
use App\Services\DealEnforcementService;
use App\Services\DealScoringService;
use App\Helpers\SlugHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DealController extends Controller
{
    protected $enforcementService;

    public function __construct(DealEnforcementService $enforcementService)
    {
        $this->middleware('auth');
        $this->enforcementService = $enforcementService;
    }

    /**
     * Display vendor's deals dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get subscription features
        $subscriptionService = app(\App\Services\SubscriptionService::class);
        $packageFeatures = $subscriptionService->getUserPackageFeatures($user);
        
        $query = Deal::where('vendor_id', $user->id);
        
        // Filters
        if ($request->filled('status')) {
            if ($request->status === 'auto_paused') {
                $query->where('auto_paused', true);
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'best_selling':
                $query->orderBy('inventory_sold', 'desc');
                break;
            case 'expiring_soon':
                $query->orderBy('expires_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $deals = $query->with(['aiAnalysis'])->paginate(20);
        
        // Stats
        $activeDeals = Deal::where('vendor_id', $user->id)->active()->count();
        $pendingDeals = Deal::where('vendor_id', $user->id)->where('status', 'pending_approval')->count();
        $totalRevenue = Deal::where('vendor_id', $user->id)
            ->sum(DB::raw('deal_price * inventory_sold'));
        $totalSold = Deal::where('vendor_id', $user->id)
            ->sum('inventory_sold');
        
        // Get subscription limits
        $subscription = $user->activeSubscription;
        $packageFeatures = $subscription ? $subscription->packageFeature() : \App\Models\PackageFeature::getByTier('starter');
        $simultaneousLimit = $packageFeatures ? $packageFeatures->simultaneous_deals : 1;
        
        return view('vendor.deals.index', compact('deals', 'activeDeals', 'pendingDeals', 'totalRevenue', 'totalSold', 'simultaneousLimit', 'packageFeatures'));
    }

    /**
     * Show deal creation form
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if training is required and completed
        if (config('training.enabled') && config('training.require_before_deal_creation')) {
            if (!$user->hasCompletedAllTraining()) {
                \Session::flash('error_flash_message', 'You must complete all required training courses before creating deals.');
                return redirect()->route('vendor.training.index');
            }
        }
        
        // Check simultaneous deal limit
        $activeDealsCount = Deal::where('vendor_id', $user->id)->active()->count();
        $check = $this->enforcementService->checkSimultaneousDealsLimit($user, $activeDealsCount);
        
        if (!$check['allowed']) {
            \Session::flash('error_flash_message', $check['message']);
            return redirect()->route('vendor.deals.index');
        }
        
        $categories = Categories::orderBy('category_name')->get();
        $packageFeatures = $user->activeSubscription 
            ? $user->activeSubscription->packageFeature() 
            : \App\Models\PackageFeature::getByTier('starter');
        
        // Lake County cities
        $cities = [
            'Fruitland Park',
            'Lady Lake',
            'Leesburg',
            'The Villages',
            'Tavares',
            'Mount Dora',
            'Eustis',
            'Umatilla',
            'Clermont',
            'Minneola',
            'Groveland',
            'Mascotte',
            'Montverde',
            'Howey-in-the-Hills',
            'Astatula',
            'Okahumpka',
        ];
        
        return view('vendor.deals.create', compact('categories', 'packageFeatures', 'cities'));
    }

    /**
     * Store new deal
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validate
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:50',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'regular_price' => 'required|numeric|min:0.01',
            'deal_price' => 'required|numeric|min:0.01|lt:regular_price',
            'inventory_total' => 'required|integer|min:1',
            'starts_at' => 'required|date|after_or_equal:now',
            'expires_at' => 'required|date|after:starts_at',
            'stripe_payment_link' => 'required|url|starts_with:https://buy.stripe.com/',
            'location_city' => 'required|string',
            'location_zip' => 'required|string|regex:/^\d{5}$/',
            'location_address' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ]);
        
        // Check simultaneous deal limit
        $activeDealsCount = Deal::where('vendor_id', $user->id)->active()->count();
        $check = $this->enforcementService->checkSimultaneousDealsLimit($user, $activeDealsCount);
        
        if (!$check['allowed']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $check['message']]);
        }
        
        // Check inventory cap
        $inventoryCheck = $this->enforcementService->checkInventoryCap($user, $validated['inventory_total']);
        
        if (!$inventoryCheck['allowed']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['inventory_total' => $inventoryCheck['message']]);
        }
        
        // Check gallery images limit
        if ($request->hasFile('gallery_images') && count($request->file('gallery_images')) > 5) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['gallery_images' => 'Maximum 5 gallery images allowed.']);
        }
        
        DB::beginTransaction();
        try {
            // Generate slug
            $slug = SlugHelper::generateDealSlug($validated['title']);
            
            // Determine status based on auto_approval feature
            $packageFeatures = $user->activeSubscription 
                ? $user->activeSubscription->packageFeature() 
                : \App\Models\PackageFeature::getByTier('starter');
            
            $autoApproved = $packageFeatures && $packageFeatures->auto_approval;
            $status = $autoApproved ? 'active' : 'pending_approval';
            
            // If auto-approved and starts_at is now, set to active immediately
            if ($autoApproved && \Carbon\Carbon::parse($validated['starts_at'])->lte(now())) {
                $status = 'active';
            }
            
            // Upload featured image
            $featuredImagePath = $this->uploadFeaturedImage($request->file('featured_image'));
            
            // Upload gallery images
            $galleryImages = [];
            if ($request->hasFile('gallery_images')) {
                $galleryImages = $this->uploadGalleryImages($request->file('gallery_images'));
            }
            
            // Calculate discount and savings
            $discountPercentage = round((($validated['regular_price'] - $validated['deal_price']) / $validated['regular_price']) * 100);
            $savingsAmount = $validated['regular_price'] - $validated['deal_price'];
            
            // Create deal
            $deal = Deal::create([
                'vendor_id' => $user->id,
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'slug' => $slug,
                'description' => $validated['description'],
                'terms_conditions' => $validated['terms_conditions'] ?? $this->getDefaultTerms($validated['expires_at']),
                'regular_price' => $validated['regular_price'],
                'deal_price' => $validated['deal_price'],
                'discount_percentage' => $discountPercentage,
                'savings_amount' => $savingsAmount,
                'featured_image' => $featuredImagePath,
                'gallery_images' => $galleryImages,
                'stripe_payment_link' => $validated['stripe_payment_link'],
                'inventory_total' => $validated['inventory_total'],
                'inventory_sold' => 0,
                'inventory_remaining' => $validated['inventory_total'],
                'location_city' => $validated['location_city'],
                'location_zip' => $validated['location_zip'],
                'location_address' => $validated['location_address'] ?? null,
                'status' => $status,
                'starts_at' => $validated['starts_at'],
                'expires_at' => $validated['expires_at'],
                'auto_approved' => $autoApproved,
            ]);
            
            // Geocode address if provided
            if ($validated['location_address']) {
                $this->geocodeAddress($deal);
            }
            
            DB::commit();
            
            // Trigger AI scoring if user has feature enabled
            $subscriptionService = app(\App\Services\SubscriptionService::class);
            if ($subscriptionService->checkFeatureAccess($user, 'ai_scoring_enabled')) {
                \App\Jobs\ScoreDealJob::dispatch($deal);
            }
            
            // Send emails
            if ($autoApproved) {
                Mail::send('emails.deal_created_confirmation', ['deal' => $deal, 'user' => $user], function ($message) use ($user) {
                    $message->to($user->email)->subject('Deal Created Successfully - ' . getcong('site_name'));
                });
            } else {
                Mail::send('emails.deal_created_confirmation', ['deal' => $deal, 'user' => $user], function ($message) use ($user) {
                    $message->to($user->email)->subject('Deal Submitted for Approval - ' . getcong('site_name'));
                });
                
                // Notify admin
                $adminEmail = getcong('site_email');
                if ($adminEmail) {
                    Mail::send('emails.admin_new_deal_pending', ['deal' => $deal], function ($message) use ($adminEmail) {
                        $message->to($adminEmail)->subject('New Deal Pending Approval - ' . getcong('site_name'));
                    });
                }
            }
            
            \Session::flash('flash_message', $autoApproved 
                ? 'Deal created successfully and is now active!' 
                : 'Deal submitted successfully! It will be reviewed by our team.');
            
            return redirect()->route('vendor.deals.index');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Deal creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create deal. Please try again.']);
        }
    }

    /**
     * Show edit form
     */
    public function edit(Deal $deal)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($deal->vendor_id !== $user->id) {
            abort(403);
        }
        
        // Can only edit draft or pending deals, or active deals (limited fields)
        if (!in_array($deal->status, ['draft', 'pending_approval']) && $deal->status !== 'active') {
            \Session::flash('error_flash_message', 'This deal cannot be edited.');
            return redirect()->route('vendor.deals.index');
        }
        
        $categories = Categories::orderBy('category_name')->get();
        $cities = [
            'Fruitland Park', 'Lady Lake', 'Leesburg', 'The Villages', 'Tavares',
            'Mount Dora', 'Eustis', 'Umatilla', 'Clermont', 'Minneola',
            'Groveland', 'Mascotte', 'Montverde', 'Howey-in-the-Hills', 'Astatula', 'Okahumpka',
        ];
        
        return view('vendor.deals.edit', compact('deal', 'categories', 'cities'));
    }

    /**
     * Update deal
     */
    public function update(Request $request, Deal $deal)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($deal->vendor_id !== $user->id) {
            abort(403);
        }
        
        // Determine which fields can be edited
        $canEditAll = in_array($deal->status, ['draft', 'pending_approval']);
        
        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:50',
            'featured_image' => ($canEditAll ? 'required' : 'nullable') . '|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'location_city' => 'required|string',
            'location_zip' => 'required|string|regex:/^\d{5}$/',
            'location_address' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ];
        
        if ($canEditAll) {
            $rules['regular_price'] = 'required|numeric|min:0.01';
            $rules['deal_price'] = 'required|numeric|min:0.01|lt:regular_price';
            $rules['inventory_total'] = 'required|integer|min:1';
            $rules['starts_at'] = 'required|date|after_or_equal:now';
            $rules['expires_at'] = 'required|date|after:starts_at';
            $rules['stripe_payment_link'] = 'required|url|starts_with:https://buy.stripe.com/';
        }
        
        $validated = $request->validate($rules);
        
        DB::beginTransaction();
        try {
            // Update slug if title changed
            if ($deal->title !== $validated['title']) {
                $deal->slug = SlugHelper::generateDealSlug($validated['title'], $deal->id);
            }
            
            // Update basic fields
            $deal->title = $validated['title'];
            $deal->category_id = $validated['category_id'];
            $deal->description = $validated['description'];
            $deal->terms_conditions = $validated['terms_conditions'] ?? $deal->terms_conditions;
            $deal->location_city = $validated['location_city'];
            $deal->location_zip = $validated['location_zip'];
            $deal->location_address = $validated['location_address'] ?? null;
            
            // Update pricing/inventory if allowed
            if ($canEditAll) {
                // Check inventory cap if changed
                if ($validated['inventory_total'] != $deal->inventory_total) {
                    $inventoryCheck = $this->enforcementService->checkInventoryCap($user, $validated['inventory_total']);
                    if (!$inventoryCheck['allowed']) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['inventory_total' => $inventoryCheck['message']]);
                    }
                }
                
                $deal->regular_price = $validated['regular_price'];
                $deal->deal_price = $validated['deal_price'];
                $deal->inventory_total = $validated['inventory_total'];
                $deal->starts_at = $validated['starts_at'];
                $deal->expires_at = $validated['expires_at'];
                $deal->stripe_payment_link = $validated['stripe_payment_link'];
                
                $discountPercentage = round((($validated['regular_price'] - $validated['deal_price']) / $validated['regular_price']) * 100);
                $deal->discount_percentage = $discountPercentage;
                $deal->savings_amount = $validated['regular_price'] - $validated['deal_price'];
            }
            
            // Handle image uploads
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($deal->featured_image) {
                    Storage::disk('public')->delete('deals/' . $deal->featured_image);
                    Storage::disk('public')->delete('deals/thumbs/' . pathinfo($deal->featured_image, PATHINFO_FILENAME) . '-thumb.' . pathinfo($deal->featured_image, PATHINFO_EXTENSION));
                }
                $deal->featured_image = $this->uploadFeaturedImage($request->file('featured_image'));
            }
            
            if ($request->hasFile('gallery_images')) {
                // Delete old gallery images
                if ($deal->gallery_images) {
                    foreach ($deal->gallery_images as $oldImage) {
                        Storage::disk('public')->delete('deals/gallery/' . $oldImage);
                    }
                }
                $deal->gallery_images = $this->uploadGalleryImages($request->file('gallery_images'));
            }
            
            // Recalculate inventory remaining
            $deal->inventory_remaining = max(0, $deal->inventory_total - $deal->inventory_sold);
            
            $deal->save();
            
            // Geocode if address changed
            if ($validated['location_address'] && $validated['location_address'] !== $deal->location_address) {
                $this->geocodeAddress($deal);
            }
            
            DB::commit();
            
            \Session::flash('flash_message', 'Deal updated successfully!');
            return redirect()->route('vendor.deals.index');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Deal update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update deal. Please try again.']);
        }
    }

    /**
     * Pause deal
     */
    public function pause(Deal $deal)
    {
        $user = Auth::user();
        
        if ($deal->vendor_id !== $user->id) {
            abort(403);
        }
        
        if ($deal->status !== 'active') {
            \Session::flash('error_flash_message', 'Only active deals can be paused.');
            return redirect()->back();
        }
        
        $deal->update(['status' => 'paused']);
        \Session::flash('flash_message', 'Deal paused successfully.');
        
        return redirect()->back();
    }

    /**
     * Resume deal
     */
    public function resume(Deal $deal)
    {
        $user = Auth::user();
        
        if ($deal->vendor_id !== $user->id) {
            abort(403);
        }
        
        if ($deal->status !== 'paused') {
            \Session::flash('error_flash_message', 'Only paused deals can be resumed.');
            return redirect()->back();
        }
        
        // Check if expired
        if ($deal->expires_at && $deal->expires_at < now()) {
            \Session::flash('error_flash_message', 'Cannot resume expired deal.');
            return redirect()->back();
        }
        
        $deal->update(['status' => 'active']);
        \Session::flash('flash_message', 'Deal resumed successfully.');
        
        return redirect()->back();
    }

    /**
     * Delete deal
     */
    public function destroy(Deal $deal)
    {
        $user = Auth::user();
        
        if ($deal->vendor_id !== $user->id) {
            abort(403);
        }
        
        // Can only delete if draft or no sales
        if (!in_array($deal->status, ['draft']) && $deal->inventory_sold > 0) {
            \Session::flash('error_flash_message', 'Cannot delete deal with sales. Please contact support.');
            return redirect()->back();
        }
        
        $deal->delete();
        \Session::flash('flash_message', 'Deal deleted successfully.');
        
        return redirect()->route('vendor.deals.index');
    }

    /**
     * Upload and process featured image
     */
    private function uploadFeaturedImage($file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = storage_path('app/public/deals');
        $thumbPath = storage_path('app/public/deals/thumbs');
        
        // Create directories if they don't exist
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        if (!file_exists($thumbPath)) {
            mkdir($thumbPath, 0755, true);
        }
        
        // Resize main image to 1200x800
        $image = Image::make($file);
        $image->fit(1200, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($path . '/' . $filename, 80);
        
        // Create thumbnail 400x267
        $thumb = Image::make($file);
        $thumb->fit(400, 267, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumb->save($thumbPath . '/' . pathinfo($filename, PATHINFO_FILENAME) . '-thumb.' . pathinfo($filename, PATHINFO_EXTENSION), 80);
        
        return $filename;
    }

    /**
     * Upload and process gallery images
     */
    private function uploadGalleryImages(array $files): array
    {
        $uploaded = [];
        $path = storage_path('app/public/deals/gallery');
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        foreach ($files as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            $image = Image::make($file);
            $image->fit(1200, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($path . '/' . $filename, 80);
            
            $uploaded[] = $filename;
        }
        
        return $uploaded;
    }

    /**
     * Geocode address to get lat/lng
     */
    private function geocodeAddress(Deal $deal)
    {
        if (!$deal->location_address) {
            return;
        }
        
        try {
            $address = urlencode($deal->location_address . ', ' . $deal->location_city . ', FL ' . $deal->location_zip);
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=" . env('GOOGLE_MAPS_API_KEY');
            
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            
            if ($data['status'] === 'OK' && isset($data['results'][0]['geometry']['location'])) {
                $location = $data['results'][0]['geometry']['location'];
                $deal->location_latitude = $location['lat'];
                $deal->location_longitude = $location['lng'];
                $deal->save();
            }
        } catch (\Exception $e) {
            \Log::warning('Geocoding failed: ' . $e->getMessage());
        }
    }

    /**
     * Get default terms and conditions
     */
    private function getDefaultTerms($expiresAt): string
    {
        $expDate = \Carbon\Carbon::parse($expiresAt)->format('F d, Y');
        return "Standard terms apply. Non-refundable. Expires {$expDate}.";
    }

    /**
     * Show AI insights for a deal
     */
    public function aiInsights(Deal $deal, DealScoringService $scoringService)
    {
        $user = Auth::user();
        
        // Verify deal belongs to user
        if ($deal->vendor_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Get or create AI analysis
        $analysis = $deal->aiAnalysis;
        
        // Get competitive pricing analysis
        $pricingAnalysis = $scoringService->analyzePricing($deal);
        
        return view('vendor.deals.ai-insights', compact('deal', 'analysis', 'pricingAnalysis'));
    }

    /**
     * Re-score a deal
     */
    public function rescore(Deal $deal)
    {
        $user = Auth::user();
        
        // Verify deal belongs to user
        if ($deal->vendor_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Dispatch scoring job
        \App\Jobs\ScoreDealJob::dispatch($deal);
        
        \Session::flash('flash_message', 'Deal re-scoring initiated. You will receive an email when complete.');
        
        return redirect()->route('vendor.deals.ai-insights', $deal);
    }
}

