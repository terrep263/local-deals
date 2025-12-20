<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Mail\TierUpgradedEmail;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $vendor = $this->vendor($request);
        $tiers = $this->availableTiers($vendor);

        return view('vendor.subscription.index', [
            'vendor' => $vendor,
            'tiers' => $tiers,
            'isFounder' => (bool) $vendor->is_founder,
            'currentTier' => $vendor->subscription_tier,
            'currentLimit' => $vendor->monthly_voucher_limit,
        ]);
    }

    public function upgrade(Request $request)
    {
        $vendor = $this->vendor($request);
        $tiers = $this->availableTiers($vendor);

        $data = $request->validate([
            'tier' => 'required|string',
        ]);

        $targetTier = $data['tier'];

        if (!isset($tiers[$targetTier])) {
            return back()->with('error', 'Invalid tier selection.');
        }

        if ($vendor->subscription_tier === $targetTier) {
            return back()->with('success', 'You are already on this tier.');
        }

        $limit = $this->tierLimit($targetTier);

        $vendor->update([
            'subscription_tier' => $targetTier,
            'monthly_voucher_limit' => $limit,
            'vouchers_used_this_month' => 0,
            'billing_period_start' => now()->toDateString(),
            'onboarding_completed' => $vendor->onboarding_completed || $vendor->profile_completed,
        ]);

        $vendor->resumeAllDeals();

        Mail::to($vendor->user->email)->send(new TierUpgradedEmail($vendor, $limit));

        return back()->with('success', 'Subscription upgraded successfully.');
    }

    public function downgrade(Request $request)
    {
        $vendor = $this->vendor($request);
        $tiers = $this->availableTiers($vendor);

        $data = $request->validate([
            'tier' => 'required|string',
        ]);

        $targetTier = $data['tier'];

        if (!isset($tiers[$targetTier])) {
            return back()->with('error', 'Invalid tier selection.');
        }

        if ($vendor->subscription_tier === $targetTier) {
            return back()->with('success', 'You are already on this tier.');
        }

        $limit = $this->tierLimit($targetTier);

        $vendor->update([
            'subscription_tier' => $targetTier,
            'monthly_voucher_limit' => $limit,
            'vouchers_used_this_month' => min($vendor->vouchers_used_this_month, $limit),
        ]);

        return back()->with('success', 'Downgrade scheduled and applied for this cycle.');
    }

    private function vendor(Request $request): VendorProfile
    {
        $vendor = $request->user()->vendorProfile;
        abort_if(!$vendor, 404, 'Vendor profile not found.');
        return $vendor;
    }

    private function availableTiers(VendorProfile $vendor): array
    {
        if ($vendor->is_founder) {
            return [
                'founder_free' => ['name' => 'Founder Free', 'price' => 0, 'limit' => 100],
                'founder_growth' => ['name' => 'Founder Growth', 'price' => 35, 'limit' => 300],
            ];
        }

        return [
            'basic' => ['name' => 'Basic', 'price' => 49, 'limit' => 600],
            'pro' => ['name' => 'Pro', 'price' => 99, 'limit' => 2000],
            'enterprise' => ['name' => 'Enterprise', 'price' => 199, 'limit' => 999999],
        ];
    }

    private function tierLimit(string $tier): int
    {
        return match ($tier) {
            'founder_free' => 100,
            'founder_growth' => 300,
            'basic' => 600,
            'pro' => 2000,
            'enterprise' => 999999,
            default => 100,
        };
    }
}

