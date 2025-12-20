<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deal;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class UpdateDealStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deals:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update deal statuses: mark expired and sold out deals, send expiration warnings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Updating deal statuses...');
        
        // Mark expired deals
        $expiredCount = Deal::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);
        
        $this->info("Marked {$expiredCount} deals as expired.");
        
        // Mark sold out deals
        $soldOutCount = Deal::where('status', 'active')
            ->whereRaw('inventory_sold >= inventory_total')
            ->where('inventory_total', '>', 0)
            ->update(['status' => 'sold_out']);
        
        $this->info("Marked {$soldOutCount} deals as sold out.");
        
        // Send expiration warnings
        $this->sendExpirationWarnings(7);
        $this->sendExpirationWarnings(3);
        $this->sendExpirationWarnings(1);
        
        $this->info('Deal status update complete.');
        
        return 0;
    }
    
    private function sendExpirationWarnings(int $days)
    {
        $deals = Deal::where('status', 'active')
            ->whereBetween('expires_at', [
                now()->addDays($days)->startOfDay(),
                now()->addDays($days)->endOfDay()
            ])
            ->with('vendor')
            ->get();
        
        foreach ($deals as $deal) {
            try {
                Mail::send('emails.deal_expiring_soon', [
                    'deal' => $deal,
                    'user' => $deal->vendor,
                    'days' => $days
                ], function ($message) use ($deal, $days) {
                    $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                    $message->to($deal->vendor->email)->subject("Deal Expires in {$days} Day" . ($days > 1 ? 's' : '') . ' - ' . getcong('site_name'));
                });
            } catch (\Exception $e) {
                \Log::error("Failed to send expiration warning email for deal {$deal->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Sent {$days}-day expiration warnings for " . $deals->count() . " deals.");
    }
}


