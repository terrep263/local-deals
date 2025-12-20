<?php

namespace App\Mail;

use App\Models\VendorProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CapacityReachedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public VendorProfile $vendor;
    public string $resetDate;
    public string $upgradeUrl;
    public int $nextTierLimit;
    public string $nextTierName;

    public function __construct(VendorProfile $vendor)
    {
        $this->vendor = $vendor;
        $this->resetDate = now()->addMonth()->startOfMonth()->format('F 1');
        $this->upgradeUrl = route('vendor.subscription.index');

        [$this->nextTierName, $this->nextTierLimit] = $this->determineNextTier($vendor);
    }

    public function build()
    {
        return $this->subject('Monthly Voucher Limit Reached')
            ->view('emails.capacity_reached')
            ->with([
                'vendor' => $this->vendor,
                'resetDate' => $this->resetDate,
                'upgradeUrl' => $this->upgradeUrl,
                'nextTierName' => $this->nextTierName,
                'nextTierLimit' => $this->nextTierLimit,
            ]);
    }

    private function determineNextTier(VendorProfile $vendor): array
    {
        $order = ['founder_free', 'founder_growth', 'basic', 'pro', 'enterprise'];
        $limits = [
            'founder_free' => 100,
            'founder_growth' => 300,
            'basic' => 600,
            'pro' => 2000,
            'enterprise' => 999999,
        ];
        $labels = [
            'founder_free' => 'Founder Growth',
            'founder_growth' => 'Founder Growth',
            'basic' => 'Pro',
            'pro' => 'Enterprise',
            'enterprise' => 'Enterprise',
        ];

        $currentIndex = array_search($vendor->subscription_tier, $order, true);
        $next = $order[min(count($order) - 1, $currentIndex + 1)] ?? 'enterprise';
        return [$labels[$vendor->subscription_tier] ?? 'Enterprise', $limits[$next] ?? 999999];
    }
}

