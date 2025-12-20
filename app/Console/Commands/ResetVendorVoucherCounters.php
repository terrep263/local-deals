<?php

namespace App\Console\Commands;

use App\Mail\MonthlyResetEmail;
use App\Models\VendorProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ResetVendorVoucherCounters extends Command
{
    protected $signature = 'vendors:reset-monthly-counters';

    protected $description = 'Reset monthly voucher counters for all vendors on 1st of month';

    public function handle(): int
    {
        $this->info('Resetting monthly voucher counters...');

        $resetCount = 0;

        VendorProfile::chunk(200, function ($vendors) use (&$resetCount) {
            foreach ($vendors as $vendor) {
                $lastMonthUsed = $vendor->vouchers_used_this_month;

                $vendor->resetMonthlyCounter();

                DB::table('vendor_monthly_resets')->insert([
                    'vendor_profile_id' => $vendor->id,
                    'month_year' => now()->subMonth()->format('Y-m'),
                    'vouchers_sold_last_month' => $lastMonthUsed,
                    'vouchers_redeemed_last_month' => null,
                    'reset_at' => now(),
                    'created_at' => now(),
                ]);

                Mail::to($vendor->user->email)->send(new MonthlyResetEmail($vendor, $lastMonthUsed));

                $resetCount++;
                $this->info("Reset {$vendor->business_name}: {$lastMonthUsed} vouchers used last month");
            }
        });

        $this->info("✓ Successfully reset {$resetCount} vendor counters");

        return Command::SUCCESS;
    }
}

