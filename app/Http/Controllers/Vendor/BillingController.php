<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    private PricingService $pricingService;
    
    public function __construct(PricingService $pricingService)
    {
        $this->middleware('auth');
        $this->middleware('vendor');
        $this->pricingService = $pricingService;
    }
    
    public function index()
    {
        $vendor = auth()->user()->vendorProfile;
        $currentPlan = $this->pricingService->getPlan($vendor->subscription_tier);
        $upgradeOptions = $this->pricingService->getUpgradeOptions($vendor);
        
        // Calculate usage percentages
        $voucherUsagePercent = $vendor->hasUnlimitedVouchers() 
            ? 100 
            : ($vendor->vouchers_used_this_month / max(1, $vendor->monthly_voucher_limit)) * 100;
            
        $dealUsagePercent = $vendor->hasUnlimitedDeals()
            ? 100
            : ($vendor->active_deals_count / max(1, $vendor->active_deals_limit)) * 100;
        
        return view('vendor.billing.index', compact(
            'vendor',
            'currentPlan',
            'upgradeOptions',
            'voucherUsagePercent',
            'dealUsagePercent'
        ));
    }
    
    public function upgrade(string $plan)
    {
        $vendor = auth()->user()->vendorProfile;
        
        if (!$this->pricingService->canUpgradeTo($vendor, $plan)) {
            return back()->with('error', 'Invalid upgrade path');
        }
        
        $targetPlan = $this->pricingService->getPlan($plan);
        
        return view('vendor.billing.upgrade', compact('vendor', 'targetPlan'));
    }
    
    public function processUpgrade(Request $request)
    {
        $request->validate([
            'plan' => 'required|string',
        ]);
        
        $vendor = auth()->user()->vendorProfile;
        
        if (!$this->pricingService->canUpgradeTo($vendor, $request->plan)) {
            return back()->with('error', 'Invalid upgrade path');
        }
        
        $targetPlan = $this->pricingService->getPlan($request->plan);
        
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            // Create or retrieve Stripe customer
            if (!$vendor->stripe_customer_id) {
                $customer = \Stripe\Customer::create([
                    'email' => auth()->user()->email,
                    'name' => $vendor->business_name,
                    'metadata' => [
                        'vendor_id' => $vendor->id,
                        'user_id' => auth()->id(),
                    ],
                ]);
                
                $vendor->update(['stripe_customer_id' => $customer->id]);
            }
            
            // Create Stripe Checkout Session for subscription
            $session = \Stripe\Checkout\Session::create([
                'customer' => $vendor->stripe_customer_id,
                'payment_method_types' => ['card'],
                'mode' => 'subscription',
                'line_items' => [[
                    'price' => $targetPlan['stripe_price_id'],
                    'quantity' => 1,
                ]],
                'success_url' => route('vendor.billing.index') . '?upgrade=success',
                'cancel_url' => route('vendor.billing.index') . '?upgrade=cancelled',
                'metadata' => [
                    'vendor_id' => $vendor->id,
                    'plan' => $request->plan,
                ],
            ]);
            
            return redirect($session->url);
            
        } catch (\Exception $e) {
            \Log::error('Upgrade error', [
                'vendor_id' => $vendor->id,
                'plan' => $request->plan,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Unable to process upgrade. Please try again.');
        }
    }
}
