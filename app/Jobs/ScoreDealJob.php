<?php

namespace App\Jobs;

use App\Models\Deal;
use App\Models\DealAIAnalysis;
use App\Services\DealScoringService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ScoreDealJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deal;

    /**
     * Create a new job instance.
     */
    public function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }

    /**
     * Execute the job.
     */
    public function handle(DealScoringService $scoringService): void
    {
        // Rate limiting: 10 scores per hour per vendor
        $key = 'ai-scoring:' . $this->deal->vendor_id;
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            Log::warning("Rate limit exceeded for AI scoring: vendor {$this->deal->vendor_id}");
            return;
        }

        RateLimiter::hit($key, 3600); // 1 hour window

        try {
            // Score the deal
            $result = $scoringService->scoreDeal($this->deal);
            
            // Update deal with score
            $this->deal->update([
                'ai_quality_score' => $result['score'],
            ]);

            // Flag low-quality deals for admin review
            if ($result['score'] < 60) {
                $this->deal->update([
                    'requires_admin_review' => true,
                    'admin_review_reason' => 'Low AI quality score: ' . $result['score'],
                ]);
                
                // Notify admins (you can create a notification class for this)
                $this->notifyAdmins();
            }

            // Save full AI analysis
            $analysis = DealAIAnalysis::updateOrCreate(
                ['deal_id' => $this->deal->id],
                [
                    'score' => $result['score'],
                    'strengths' => $result['strengths'],
                    'weaknesses' => $result['weaknesses'],
                    'suggestions' => $result['suggestions'],
                    'competitive_analysis' => $result['competitive_analysis'],
                    'analyzed_at' => now(),
                ]
            );

            // Track usage and cost
            $this->trackUsage($result['tokens_used'] ?? 0);

            // Send email to vendor with AI results
            $this->sendVendorEmail($result);

        } catch (\Exception $e) {
            Log::error('ScoreDealJob failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Track AI usage and cost
     */
    protected function trackUsage(int $tokensUsed): void
    {
        if ($tokensUsed > 0) {
            $cost = $this->calculateCost($tokensUsed);
            
            \App\Models\AIUsage::create([
                'user_id' => $this->deal->vendor_id,
                'feature' => 'deal_scoring',
                'tokens_used' => $tokensUsed,
                'cost_estimate' => $cost,
            ]);
        }
    }

    /**
     * Calculate cost estimate
     */
    protected function calculateCost(int $tokens): float
    {
        // Claude Sonnet 4: $3 per million input tokens, $15 per million output tokens
        // Estimate: 50% input, 50% output
        $inputCost = ($tokens * 0.5) * (3 / 1000000);
        $outputCost = ($tokens * 0.5) * (15 / 1000000);
        
        return round($inputCost + $outputCost, 6);
    }

    /**
     * Send email to vendor with AI analysis
     */
    protected function sendVendorEmail(array $result): void
    {
        try {
            $vendor = $this->deal->vendor;
            
            if ($vendor && $vendor->email) {
                Mail::send('emails.deal_ai_analysis', [
                    'deal' => $this->deal,
                    'result' => $result,
                ], function ($message) use ($vendor) {
                    $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                    $message->to($vendor->email)
                            ->subject('AI Analysis Complete: ' . $this->deal->title);
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send AI analysis email: ' . $e->getMessage());
        }
    }

    /**
     * Notify admins about low-quality deal
     */
    protected function notifyAdmins(): void
    {
        // Get admin users (you may need to adjust this based on your user model)
        $admins = \App\Models\User::where('role', 'admin')
            ->orWhere('is_admin', true)
            ->get();

        foreach ($admins as $admin) {
            try {
                Mail::send('emails.admin_low_quality_deal', [
                    'deal' => $this->deal,
                ], function ($message) use ($admin) {
                    $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                    $message->to($admin->email)
                            ->subject('Low Quality Deal Flagged: ' . $this->deal->title);
                });
            } catch (\Exception $e) {
                Log::error('Failed to notify admin: ' . $e->getMessage());
            }
        }
    }
}


