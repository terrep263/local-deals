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

                // Subscription events (existing)
                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                case 'customer.subscription.deleted':
                case 'invoice.payment_succeeded':
                case 'invoice.payment_failed':
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
}








