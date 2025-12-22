<?php

namespace App\Http\Controllers;

use App\Models\DealPurchase;
use App\Models\VendorProfile;
use App\Models\WebhookEvent;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook verification error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Verification error'], 400);
        }

        Log::info('Verified Stripe webhook', [
            'type' => $event->type,
            'id' => $event->id
        ]);

        // Check if event already processed (idempotency)
        if (WebhookEvent::hasBeenProcessed($event->id)) {
            Log::info('Webhook event already processed', [
                'event_id' => $event->id
            ]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        // Store event for idempotency tracking
        $webhookEvent = WebhookEvent::create([
            'stripe_event_id' => $event->id,
            'type' => $event->type,
            'payload' => json_decode($payload, true)
        ]);

        try {
            // Handle payment-related events (for deal purchases)
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutSessionCompleted($event);
                    break;

                case 'payment_intent.succeeded':
                    Log::info('Payment intent succeeded', [
                        'payment_intent_id' => $event->data->object->id
                    ]);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event);
                    break;

                // Subscription events (new pricing system)
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event);
                    break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event);
                    break;

                // Legacy subscription handling (keep for backward compatibility)
                case 'customer.subscription.updated':
                case 'invoice.payment_succeeded':
                    $this->subscriptionService->handleWebhook($event->toArray());
                    break;

                default:
                    Log::info('Unhandled webhook event type', [
                        'type' => $event->type
                    ]);
            }

            // Mark event as processed
            $webhookEvent->markProcessed();

            Log::info('Webhook event marked as processed', [
                'event_id' => $event->id
            ]);

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Error processing webhook', [
                'type' => $event->type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Still return 200 so Stripe doesn't retry
            return response()->json(['status' => 'received'], 200);
        }
    }

    /**
     * Handle checkout.session.completed event
     */
    protected function handleCheckoutSessionCompleted($event)
    {
        $session = $event->data->object;

        Log::info('Processing checkout.session.completed', [
            'session_id' => $session->id,
            'payment_status' => $session->payment_status
        ]);

        // Only process completed payments
        if ($session->payment_status !== 'paid') {
            Log::warning('Checkout session not paid', [
                'session_id' => $session->id,
                'status' => $session->payment_status
            ]);
            return;
        }

        // Find the purchase record
        $purchase = DealPurchase::where('stripe_checkout_session_id', $session->id)
            ->first();

        if (!$purchase) {
            Log::error('Purchase not found for session', [
                'session_id' => $session->id
            ]);
            return;
        }

        // Check if already processed (prevent duplicate processing)
        if ($purchase->status === 'completed') {
            Log::info('Purchase already processed', [
                'purchase_id' => $purchase->id
            ]);
            return;
        }

        // Update purchase status
        $purchase->update([
            'status' => 'completed',
            'stripe_payment_intent_id' => $session->payment_intent,
            'purchase_date' => now()
        ]);

        Log::info('Purchase marked as completed', [
            'purchase_id' => $purchase->id,
            'deal_id' => $purchase->deal_id,
            'user_id' => $purchase->user_id
        ]);

        // Increment vendor's voucher counter
        $this->incrementVendorVoucherCount($purchase->vendor_profile_id);

        // TODO: Trigger voucher generation
        // This will be handled by the Voucher Generation system

        Log::info('Payment processing completed', [
            'purchase_id' => $purchase->id
        ]);
    }

    /**
     * Increment vendor's voucher usage counter
     */
    protected function incrementVendorVoucherCount($vendorProfileId)
    {
        // Get vendor from user_id (deal_id's user relationship)
        // For now, just log - we'll handle vendor capacity later
        
        Log::info('Vendor voucher counter would increment', [
            'vendor_id' => $vendorProfileId
        ]);
    }

    /**
     * Handle payment_intent.payment_failed event
     */
    protected function handlePaymentFailed($event)
    {
        $paymentIntent = $event->data->object;

        Log::warning('Payment failed', [
            'payment_intent_id' => $paymentIntent->id,
            'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error'
        ]);

        // Find purchase by payment intent or by Stripe session metadata
        $purchase = DealPurchase::where('stripe_payment_intent_id', $paymentIntent->id)
            ->first();

        if ($purchase) {
            $purchase->update([
                'status' => 'failed'
            ]);

            Log::info('Purchase marked as failed', [
                'purchase_id' => $purchase->id
            ]);
        }
    }

    /**
     * Handle customer.subscription.created event
     */
    protected function handleSubscriptionCreated($event)
    {
        $subscription = $event->data->object;
        
        Log::info('Subscription created', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer
        ]);
        
        // Find vendor by customer ID
        $vendor = VendorProfile::where('stripe_customer_id', $subscription->customer)
            ->first();
            
        if (!$vendor) {
            Log::error('Vendor not found for subscription', [
                'customer_id' => $subscription->customer
            ]);
            return;
        }
        
        // Get plan from subscription metadata
        $planSlug = $subscription->metadata['plan'] ?? null;
        
        if (!$planSlug) {
            Log::error('No plan in subscription metadata', [
                'subscription_id' => $subscription->id
            ]);
            return;
        }
        
        // Apply plan limits
        $pricingService = app(\App\Services\PricingService::class);
        $pricingService->applyPlanLimits($vendor, $planSlug);
        
        // Update vendor
        $vendor->update([
            'stripe_subscription_id' => $subscription->id,
            'subscription_started_at' => now(),
            'subscription_ends_at' => null,
        ]);
        
        // If upgrading from founder, revoke founder status
        if ($vendor->isFounder() && $planSlug !== 'founder_upgrade') {
            $vendor->update(['is_founder' => false]);
        }
        
        Log::info('Subscription activated', [
            'vendor_id' => $vendor->id,
            'plan' => $planSlug
        ]);
    }

    /**
     * Handle customer.subscription.deleted event
     */
    protected function handleSubscriptionDeleted($event)
    {
        $subscription = $event->data->object;
        
        Log::warning('Subscription cancelled', [
            'subscription_id' => $subscription->id
        ]);
        
        $vendor = VendorProfile::where('stripe_subscription_id', $subscription->id)
            ->first();
            
        if (!$vendor) {
            Log::error('Vendor not found for cancelled subscription', [
                'subscription_id' => $subscription->id
            ]);
            return;
        }
        
        // Downgrade to free tier
        $pricingService = app(\App\Services\PricingService::class);
        $pricingService->applyPlanLimits($vendor, 'founder');
        
        $vendor->update([
            'stripe_subscription_id' => null,
            'subscription_ends_at' => now(),
        ]);
        
        Log::info('Vendor downgraded to free tier', [
            'vendor_id' => $vendor->id
        ]);
    }

    /**
     * Handle invoice.payment_failed event (for subscriptions)
     */
    protected function handleInvoicePaymentFailed($event)
    {
        $invoice = $event->data->object;
        
        Log::warning('Invoice payment failed', [
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer
        ]);
        
        $vendor = VendorProfile::where('stripe_customer_id', $invoice->customer)
            ->first();
            
        if ($vendor) {
            Log::info('Payment failure notification sent', [
                'vendor_id' => $vendor->id
            ]);
            
            // TODO: Send payment failed email to vendor
            // \Mail::to($vendor->user)->send(new \App\Mail\PaymentFailedEmail($vendor));
        }
    }
}








