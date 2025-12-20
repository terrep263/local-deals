<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Track view event
     */
    public function trackView(Request $request)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'session_id' => 'nullable|string',
        ]);

        // Rate limiting
        $key = 'analytics-view:' . ($request->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 100)) {
            return response()->json(['success' => false, 'message' => 'Rate limit exceeded'], 429);
        }
        RateLimiter::hit($key, 60); // 1 minute window

        try {
            $deal = Deal::findOrFail($request->deal_id);
            $deal->increment('view_count');

            // Check if vendor has analytics_access to log detailed event
            $vendor = $deal->vendor;
            if ($vendor) {
                $subscriptionService = app(\App\Services\SubscriptionService::class);
                if ($subscriptionService->checkFeatureAccess($vendor, 'analytics_access')) {
                    AnalyticsEvent::create([
                        'deal_id' => $deal->id,
                        'event_type' => 'view',
                        'user_id' => auth()->id(),
                        'session_id' => $request->session_id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'referrer' => $request->header('referer'),
                        'created_at' => now(),
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Track view failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Track click event
     */
    public function trackClick(Request $request)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'session_id' => 'nullable|string',
        ]);

        // Rate limiting
        $key = 'analytics-click:' . ($request->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 50)) {
            return response()->json(['success' => false, 'message' => 'Rate limit exceeded'], 429);
        }
        RateLimiter::hit($key, 60);

        try {
            $deal = Deal::findOrFail($request->deal_id);
            $deal->increment('click_count');

            // Check if vendor has analytics_access
            $vendor = $deal->vendor;
            if ($vendor) {
                $subscriptionService = app(\App\Services\SubscriptionService::class);
                if ($subscriptionService->checkFeatureAccess($vendor, 'analytics_access')) {
                    AnalyticsEvent::create([
                        'deal_id' => $deal->id,
                        'event_type' => 'click',
                        'user_id' => auth()->id(),
                        'session_id' => $request->session_id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'referrer' => $request->header('referer'),
                        'created_at' => now(),
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Track click failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }
}


