<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'tier' => 'required|in:basic,pro,enterprise',
        ]);

        $user = Auth::user();

        if ($user->activeSubscription) {
            \Session::flash('error_flash_message', 'You already have an active subscription. Please manage it from your dashboard.');
            return redirect()->route('pricing');
        }

        try {
            $checkoutUrl = $this->subscriptionService->createCheckoutSession($user, $request->tier);
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error('Checkout creation failed', ['error' => $e->getMessage()]);
            \Session::flash('error_flash_message', 'Failed to create checkout session. Please try again.');
            return redirect()->route('pricing');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            \Session::flash('error_flash_message', 'Invalid session.');
            return redirect()->route('pricing');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            return view('subscription.success', compact('session'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve checkout session', ['error' => $e->getMessage()]);
            \Session::flash('error_flash_message', 'Failed to verify subscription. Please contact support.');
            return redirect()->route('dashboard');
        }
    }

    public function cancel()
    {
        return view('subscription.cancel');
    }

    public function portal()
    {
        $user = Auth::user();

        if (!$user->stripe_customer_id) {
            \Session::flash('error_flash_message', 'No subscription found.');
            return redirect()->route('dashboard');
        }

        try {
            $portalUrl = $this->subscriptionService->getCustomerPortalUrl($user);
            return redirect($portalUrl);
        } catch (\Exception $e) {
            Log::error('Failed to create portal session', ['error' => $e->getMessage()]);
            \Session::flash('error_flash_message', 'Failed to open customer portal. Please try again.');
            return redirect()->route('dashboard');
        }
    }
}








