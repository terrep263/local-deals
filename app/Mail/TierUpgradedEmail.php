<?php

namespace App\Mail;

use App\Models\VendorProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TierUpgradedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public VendorProfile $vendor;
    public int $voucherLimit;

    public function __construct(VendorProfile $vendor, int $voucherLimit)
    {
        $this->vendor = $vendor;
        $this->voucherLimit = $voucherLimit;
    }

    public function build()
    {
        return $this->subject('Subscription Upgraded - Lake County Local Deals')
            ->view('emails.tier_upgraded')
            ->with([
                'vendor' => $this->vendor,
                'voucherLimit' => $this->voucherLimit,
            ]);
    }
}

