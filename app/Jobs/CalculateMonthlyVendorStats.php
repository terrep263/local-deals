<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\UpgradeDetectionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CalculateMonthlyVendorStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $year;
    protected $month;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(?int $year = null, ?int $month = null)
    {
        $this->year = $year ?? now()->subMonth()->year;
        $this->month = $month ?? now()->subMonth()->month;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CommissionService $commissionService, UpgradeDetectionService $upgradeService)
    {
        Log::info('Starting monthly vendor stats calculation', [
            'year' => $this->year,
            'month' => $this->month,
        ]);

        // Get all vendors (non-admin users)
        $vendors = User::where('usertype', '!=', 'Admin')
            ->where('usertype', '!=', 'admin')
            ->get();

        foreach ($vendors as $vendor) {
            try {
                // Calculate monthly stats
                $stats = $commissionService->getMonthlyStats($vendor->id, $this->year, $this->month);
                
                // Check for upgrade opportunities
                $upgradeService->checkForUpgradeOpportunities($vendor->id);
                
                // Send monthly summary email
                $this->sendMonthlyReport($vendor, $stats);
                
            } catch (\Exception $e) {
                Log::error('Failed to calculate stats for vendor', [
                    'vendor_id' => $vendor->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Monthly vendor stats calculation completed', [
            'vendors_processed' => $vendors->count(),
        ]);
    }

    /**
     * Send monthly performance report to vendor
     */
    protected function sendMonthlyReport(User $vendor, $stats)
    {
        try {
            Mail::send('emails.vendor.monthly_report', [
                'vendor' => $vendor,
                'stats' => $stats,
            ], function ($message) use ($vendor) {
                $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                $message->to($vendor->email)
                    ->subject('Your Lake County Local Deals Monthly Report');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send monthly report email', [
                'vendor_id' => $vendor->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}


