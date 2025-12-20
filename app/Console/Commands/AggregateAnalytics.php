<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deal;
use App\Models\AnalyticsEvent;
use App\Models\DealPurchase;
use App\Models\DealDailyStat;
use Carbon\Carbon;

class AggregateAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate daily analytics stats for all deals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting analytics aggregation...');
        
        // Set date to yesterday
        $date = Carbon::yesterday();
        
        $this->info("Aggregating stats for: {$date->format('Y-m-d')}");
        
        // Chunk through all deals
        Deal::chunk(100, function ($deals) use ($date) {
            foreach ($deals as $deal) {
                // Count views for this date
                $views = AnalyticsEvent::where('deal_id', $deal->id)
                    ->where('event_type', 'view')
                    ->whereDate('created_at', $date)
                    ->count();
                
                // Count clicks for this date
                $clicks = AnalyticsEvent::where('deal_id', $deal->id)
                    ->where('event_type', 'click')
                    ->whereDate('created_at', $date)
                    ->count();
                
                // Count purchases for this date
                $purchases = DealPurchase::where('deal_id', $deal->id)
                    ->whereDate('purchase_date', $date)
                    ->count();
                
                // Sum revenue for this date
                $revenue = DealPurchase::where('deal_id', $deal->id)
                    ->whereDate('purchase_date', $date)
                    ->sum('purchase_amount');
                
                // Create or update daily stat
                DealDailyStat::updateOrCreate(
                    [
                        'deal_id' => $deal->id,
                        'date' => $date,
                    ],
                    [
                        'views' => $views,
                        'clicks' => $clicks,
                        'purchases' => $purchases,
                        'revenue' => $revenue ?? 0,
                    ]
                );
            }
        });
        
        $this->info('Analytics aggregation complete!');
        
        return Command::SUCCESS;
    }
}


