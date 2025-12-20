<?php

namespace App\Mail;

use App\Models\VendorProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonthlyResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public VendorProfile $vendor;
    public int $vouchersUsedLastMonth;
    public string $resetMonth;

    public function __construct(VendorProfile $vendor, int $vouchersUsedLastMonth)
    {
        $this->vendor = $vendor;
        $this->vouchersUsedLastMonth = $vouchersUsedLastMonth;
        $this->resetMonth = now()->format('F Y');
    }

    public function build()
    {
        return $this->subject('Monthly Voucher Counter Reset - ' . $this->resetMonth)
            ->view('emails.monthly_reset')
            ->with([
                'vendor' => $this->vendor,
                'vouchersUsedLastMonth' => $this->vouchersUsedLastMonth,
                'resetMonth' => $this->resetMonth,
                'resetDate' => now()->format('F'),
                'upgradeUrl' => route('vendor.subscription.index'),
                'dashboardUrl' => route('vendor.deals.index'),
            ]);
    }
}

