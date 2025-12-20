<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;

class CheckSubscriptionFeature
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $feature
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $feature)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        $hasAccess = $this->subscriptionService->checkFeatureAccess($user, $feature);

        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'This feature requires a higher subscription tier.',
                    'upgrade_required' => true,
                    'upgrade_url' => route('pricing')
                ], 403);
            }

            $tierName = $this->getRequiredTierName($feature);
            \Session::flash('error_flash_message', "This feature requires {$tierName}. Upgrade now!");
            return redirect()->route('pricing');
        }

        return $next($request);
    }

    private function getRequiredTierName(string $feature): string
    {
        $tierMap = [
            'ai_scoring_enabled' => 'Pro Plan',
            'analytics_access' => 'Basic Plan',
            'priority_placement' => 'Pro Plan',
            'featured_placement' => 'Pro Plan',
            'api_access' => 'Enterprise Plan',
            'white_label' => 'Enterprise Plan',
        ];

        return $tierMap[$feature] ?? 'a higher subscription tier';
    }
}
