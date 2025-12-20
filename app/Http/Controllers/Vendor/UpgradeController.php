<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\UpgradeDetectionService;
use App\Services\SubscriptionService;
use App\Models\UpgradeSuggestion;
use App\Models\PackageFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class UpgradeController extends Controller
{
    protected $upgradeService;
    protected $subscriptionService;
    
    public function __construct(
        UpgradeDetectionService $upgradeService,
        SubscriptionService $subscriptionService
    ) {
        $this->middleware('auth');
        $this->upgradeService = $upgradeService;
        $this->subscriptionService = $subscriptionService;
    }
    
    /**
     * Show upgrade options page
     */
    public function index()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;
        $currentTier = $subscription ? $subscription->package_tier : 'starter';
        
        // Get all tiers
        $tiers = [
            'starter' => PackageFeature::getByTier('starter'),
            'basic' => PackageFeature::getByTier('basic'),
            'pro' => PackageFeature::getByTier('pro'),
            'enterprise' => PackageFeature::getByTier('enterprise'),
        ];
        
        // Get current month stats
        $commissionService = app(\App\Services\CommissionService::class);
        $stats = $commissionService->getMonthlyStats($user->id);
        
        // Get active upgrade suggestion
        $suggestion = $this->upgradeService->getActiveUpgradeSuggestion($user->id);
        if ($suggestion) {
            $suggestion->markAsShown();
        }
        
        // Calculate savings for each tier
        $tierSavings = [];
        foreach ($tiers as $tierName => $tierFeatures) {
            if ($tierFeatures && $tierName !== $currentTier) {
                $tierSavings[$tierName] = $this->upgradeService->getSavingsCalculation($user->id, $tierName);
            }
        }
        
        return view('vendor.upgrade.index', compact(
            'user', 'subscription', 'currentTier', 'tiers', 'stats', 'suggestion', 'tierSavings'
        ));
    }
    
    /**
     * Process upgrade
     */
    public function upgrade(Request $request)
    {
        $request->validate([
            'tier' => 'required|in:starter,basic,pro,enterprise',
        ]);
        
        $user = Auth::user();
        $targetTier = $request->tier;
        
        $subscription = $user->activeSubscription;
        $currentTier = $subscription ? $subscription->package_tier : 'starter';
        
        // Check if it's actually an upgrade
        $tierOrder = ['starter', 'basic', 'pro', 'enterprise'];
        $currentIndex = array_search($currentTier, $tierOrder);
        $targetIndex = array_search($targetTier, $tierOrder);
        
        if ($targetIndex <= $currentIndex) {
            // Downgrade - check if allowed
            $commissionService = app(\App\Services\CommissionService::class);
            $currentCount = $commissionService->getCurrentMonthVoucherCount($user->id);
            $targetFeatures = PackageFeature::getByTier($targetTier);
            
            if ($targetFeatures && $targetFeatures->monthly_voucher_limit && $currentCount > $targetFeatures->monthly_voucher_limit) {
                Session::flash('error_flash_message', 
                    "You sold {$currentCount} vouchers this month. The {$targetTier} plan maxes at {$targetFeatures->monthly_voucher_limit}. Please reduce volume first or choose a higher tier.");
                return redirect()->back();
            }
            
            // Schedule downgrade for next billing cycle
            Session::flash('flash_message', 
                "Your downgrade to {$targetTier} will take effect at your next billing cycle.");
            // TODO: Implement downgrade scheduling
            return redirect()->back();
        }
        
        // Process upgrade
        try {
            if ($subscription) {
                // Update existing Stripe subscription
                $this->subscriptionService->changeSubscriptionTier($subscription, $targetTier);
            } else {
                // Create new subscription
                $this->subscriptionService->createSubscription($user, $targetTier);
            }
            
            // Mark upgrade suggestion as converted
            $suggestion = $this->upgradeService->getActiveUpgradeSuggestion($user->id);
            if ($suggestion && $suggestion->to_tier === $targetTier) {
                $suggestion->markAsConverted();
            }
            
            Session::flash('flash_message', "Successfully upgraded to {$targetTier} tier!");
            return redirect()->route('vendor.upgrade.index');
            
        } catch (\Exception $e) {
            Log::error('Upgrade failed: ' . $e->getMessage());
            Session::flash('error_flash_message', 'Failed to process upgrade. Please try again or contact support.');
            return redirect()->back();
        }
    }
    
    /**
     * Dismiss upgrade suggestion
     */
    public function dismissSuggestion($suggestionId)
    {
        $suggestion = UpgradeSuggestion::findOrFail($suggestionId);
        
        if ($suggestion->user_id !== Auth::id()) {
            abort(403);
        }
        
        $suggestion->dismiss();
        
        return response()->json(['success' => true]);
    }
}


