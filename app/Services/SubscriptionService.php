<?php

namespace App\Services;

use App\Models\User;
use App\Models\Subscription;
use App\Models\PackageFeature;
use App\Models\SubscriptionEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionService
{
    protected function initializeStripe()
    {
        if (!class_exists('\Stripe\Stripe')) {
            throw new \Exception('Stripe PHP package is not installed. Run: composer require stripe/stripe-php');
        }
        
        $stripeSecret = config('services.stripe.secret');
        if ($stripeSecret) {
            \Stripe\Stripe::setApiKey($stripeSecret);
        }
    }

    public function createCheckoutSession(User $user, string $tier): string
    {
        $this->initializeStripe();
        
        try {
            $priceId = config("services.stripe.prices.{$tier}");
            
            if (!$priceId) {
                throw new \Exception("Price ID not found for tier: {$tier}");
            }

            $customerId = $this->getOrCreateCustomer($user);

            $session = \Stripe\Checkout\Session::create([
                'customer' => $customerId,
                'mode' => 'subscription',
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'success_url' => url('/subscription/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/pricing'),
                'metadata' => [
                    'user_id' => $user->id,
                    'package_tier' => $tier,
                ],
            ]);

            return $session->id;
        } catch (\Exception $e) {
            Log::error('Stripe checkout session creation failed', [
                'user_id' => $user->id,
                'tier' => $tier,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getOrCreateCustomer(User $user): string
    {
        $this->initializeStripe();
        
        if ($user->stripe_customer_id) {
            try {
                \Stripe\Customer::retrieve($user->stripe_customer_id);
                return $user->stripe_customer_id;
            } catch (\Exception $e) {
                // Customer doesn't exist, create new one
            }
        }

        try {
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            $user->update(['stripe_customer_id' => $customer->id]);
            return $customer->id;
        } catch (\Exception $e) {
            Log::error('Failed to create Stripe customer', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function createSubscription(User $user, string $tier): Subscription
    {
        $this->initializeStripe();
        
        try {
            $priceId = config("services.stripe.prices.{$tier}");
            if (!$priceId) {
                throw new \Exception("Price ID not found for tier: {$tier}");
            }

            $customerId = $this->getOrCreateCustomer($user);

            $subscription = \Stripe\Subscription::create([
                'customer' => $customerId,
                'items' => [[
                    'price' => $priceId,
                ]],
                'metadata' => [
                    'user_id' => $user->id,
                    'package_tier' => $tier,
                ],
            ]);

            return $this->syncSubscriptionFromStripe($subscription, $user);
        } catch (\Exception $e) {
            Log::error('Failed to create Stripe subscription', [
                'user_id' => $user->id,
                'tier' => $tier,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function changeSubscriptionTier(Subscription $subscription, string $newTier): Subscription
    {
        $this->initializeStripe();
        
        try {
            $priceId = config("services.stripe.prices.{$newTier}");
            if (!$priceId) {
                throw new \Exception("Price ID not found for tier: {$newTier}");
            }

            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);
            
            // Update subscription item to new price (Stripe handles proration automatically)
            \Stripe\Subscription::update($subscription->stripe_subscription_id, [
                'items' => [[
                    'id' => $stripeSubscription->items->data[0]->id,
                    'price' => $priceId,
                ]],
                'proration_behavior' => 'always_invoice',
                'metadata' => [
                    'user_id' => $subscription->user_id,
                    'package_tier' => $newTier,
                ],
            ]);

            // Sync updated subscription
            $updatedSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);
            return $this->syncSubscriptionFromStripe($updatedSubscription, $subscription->user);
        } catch (\Exception $e) {
            Log::error('Failed to change subscription tier', [
                'subscription_id' => $subscription->id,
                'new_tier' => $newTier,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function syncSubscriptionFromStripe($stripeSubscription, User $user): Subscription
    {
        $subscription = Subscription::updateOrCreate(
            [
                'stripe_subscription_id' => $stripeSubscription->id,
            ],
            [
                'user_id' => $user->id,
                'stripe_customer_id' => $stripeSubscription->customer,
                'stripe_price_id' => $stripeSubscription->items->data[0]->price->id,
                'package_tier' => $stripeSubscription->metadata->package_tier ?? 'starter',
                'status' => $stripeSubscription->status,
                'current_period_start' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_start),
                'current_period_end' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
                'cancel_at_period_end' => $stripeSubscription->cancel_at_period_end ?? false,
                'trial_ends_at' => $stripeSubscription->trial_end ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end) : null,
            ]
        );

        // Record event
        SubscriptionEvent::create([
            'subscription_id' => $subscription->id,
            'event_type' => 'subscription.updated',
            'stripe_event_id' => 'sync_' . time(),
            'data' => json_encode($stripeSubscription),
        ]);

        return $subscription;
    }

    public function cancelSubscription(Subscription $subscription, bool $immediately = false): bool
    {
        return $subscription->cancel($immediately);
    }

    public function getUserPackageFeatures(User $user): ?PackageFeature
    {
        $subscription = $user->activeSubscription;
        
        if ($subscription) {
            return $subscription->packageFeature();
        }
        
        return PackageFeature::getByTier('starter');
    }
}
