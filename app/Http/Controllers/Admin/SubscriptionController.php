<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request)
    {
        try {
            $query = Subscription::with('user');
        } catch (\Exception $e) {
            // If subscriptions table doesn't exist, return empty results
            return view('admin.subscriptions.index', [
                'subscriptions' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 50),
                'stats' => [
                    'active' => 0,
                    'mrr' => 0,
                    'churn' => 0,
                    'new_this_month' => 0,
                ],
            ]);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tier')) {
            $query->where('package_tier', $request->tier);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            try {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
            } catch (\Exception $e) {
                // If user relationship doesn't exist, skip this filter
            }
        }

        try {
            $subscriptions = $query->orderBy('created_at', 'desc')->paginate(50);
        } catch (\Exception $e) {
            $subscriptions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 50);
        }

        // Stats
        try {
            $stats = [
                'active' => Subscription::where('status', 'active')->count(),
                'mrr' => 0, // Will be calculated if package_features table exists
                'churn' => Subscription::where('status', 'canceled')
                    ->whereMonth('updated_at', now()->month)
                    ->count(),
                'new_this_month' => Subscription::whereMonth('created_at', now()->month)->count(),
                'past_due' => Subscription::where('status', 'past_due')->count(),
            ];
        } catch (\Exception $e) {
            $stats = [
                'active' => 0,
                'mrr' => 0,
                'churn' => 0,
                'new_this_month' => 0,
                'past_due' => 0,
            ];
        }
        
        // Calculate MRR if package_features table exists
        try {
            $stats['mrr'] = Subscription::where('status', 'active')
                ->join('package_features', 'subscriptions.package_tier', '=', 'package_features.package_tier')
                ->sum('package_features.monthly_price');
        } catch (\Exception $e) {
            // package_features table doesn't exist, MRR stays 0
        }

        return view('admin.subscriptions.index', compact('subscriptions', 'stats'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'events']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function cancel(Request $request, Subscription $subscription)
    {
        $immediately = $request->boolean('immediately', false);

        try {
            $this->subscriptionService->cancelSubscription($subscription, $immediately);
            \Session::flash('success_flash_message', 'Subscription canceled successfully.');
        } catch (\Exception $e) {
            Log::error('Admin subscription cancellation failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
            \Session::flash('error_flash_message', 'Failed to cancel subscription.');
        }

        return redirect()->route('admin.subscriptions.show', $subscription);
    }
}

