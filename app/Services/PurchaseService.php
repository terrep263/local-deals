<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\DealPurchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;

class PurchaseService
{
    protected $stripe;
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
        $this->voucherService = $voucherService;
    }

    /**
     * Create Stripe Checkout Session for deal purchase
     */
    public function createCheckoutSession(Deal $deal, int $quantity, array $customerData, User $user = null): array
    {
        // Validate deal availability
        if (!$this->isDealAvailable($deal, $quantity)) {
            throw new \Exception('Deal is not available for purchase.');
        }

        // Calculate total amount
        $unitAmount = $deal->price * 100; // Convert to cents
        $totalAmount = $unitAmount * $quantity;

        try {
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $deal->title,
                            'description' => $deal->short_description ?? '',
                            'images' => [$deal->image_url ?? ''],
                        ],
                        'unit_amount' => $unitAmount,
                    ],
                    'quantity' => $quantity,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('deals.show', $deal->slug) . '?cancelled=1',
                'customer_email' => $customerData['email'],
                'metadata' => [
                    'deal_id' => $deal->id,
                    'quantity' => $quantity,
                    'user_id' => $user?->id ?? 'guest',
                    'customer_name' => $customerData['name'] ?? '',
                    'customer_phone' => $customerData['phone'] ?? '',
                ],
            ]);

            return [
                'success' => true,
                'session_id' => $session->id,
                'session_url' => $session->url,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(string $sessionId): DealPurchase
    {
        $session = $this->stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent'],
        ]);

        $metadata = $session->metadata;

        return DB::transaction(function () use ($session, $metadata) {
            // Create purchase record
            $purchase = DealPurchase::create([
                'deal_id' => $metadata->deal_id,
                'user_id' => $metadata->user_id !== 'guest' ? $metadata->user_id : null,
                'quantity' => $metadata->quantity,
                'consumer_email' => $session->customer_email ?? $session->customer_details->email,
                'consumer_name' => $metadata->customer_name ?? $session->customer_details->name,
                'customer_phone' => $metadata->customer_phone ?? $session->customer_details->phone,
                'purchase_amount' => $session->amount_total / 100, // Convert from cents
                'stripe_payment_intent_id' => $session->payment_intent->id ?? null,
                'stripe_checkout_session_id' => $sessionId,
                'status' => 'completed',
                'purchase_date' => now(),
            ]);

            // Update deal inventory
            $deal = Deal::find($metadata->deal_id);
            $deal->decrement('spots_available', $metadata->quantity);

            // Check if deal is sold out
            if ($deal->spots_available <= 0) {
                $deal->update(['status' => 'sold_out']);
            }

            // Generate vouchers
            $vouchers = $this->voucherService->generateVouchers($purchase);

            // Send confirmation email
            $this->sendPurchaseConfirmationEmail($purchase, $vouchers);

            return $purchase;
        });
    }

    /**
     * Check if deal is available for purchase
     */
    private function isDealAvailable(Deal $deal, int $quantity): bool
    {
        if ($deal->status !== 'active') {
            return false;
        }

        if ($deal->end_date && $deal->end_date->isPast()) {
            return false;
        }

        if ($deal->spots_available !== null && $deal->spots_available < $quantity) {
            return false;
        }

        return true;
    }

    /**
     * Send purchase confirmation email
     */
    private function sendPurchaseConfirmationEmail(DealPurchase $purchase, array $vouchers): void
    {
        try {
            Mail::send('emails.purchase-confirmation', [
                'purchase' => $purchase,
                'deal' => $purchase->deal,
                'vouchers' => $vouchers,
            ], function ($message) use ($purchase) {
                $message->to($purchase->consumer_email, $purchase->consumer_name)
                    ->subject('Your Purchase Confirmation - ' . $purchase->deal->title);
            });

            $purchase->update(['voucher_sent_at' => now()]);
        } catch (\Exception $e) {
            \Log::error('Failed to send purchase confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Get purchase by confirmation code
     */
    public function getPurchaseByCode(string $code): ?DealPurchase
    {
        return DealPurchase::with(['deal', 'vouchers'])
            ->where('confirmation_code', $code)
            ->first();
    }

    /**
     * Process refund
     */
    public function processRefund(DealPurchase $purchase, string $reason = null): array
    {
        try {
            // Refund via Stripe
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $purchase->stripe_payment_intent_id,
                'reason' => 'requested_by_customer',
            ]);

            // Update purchase status
            $purchase->update([
                'status' => 'refunded',
                'notes' => $reason ?? 'Refund processed',
            ]);

            // Cancel all vouchers
            $purchase->vouchers()->update(['status' => 'cancelled']);

            // Restore deal inventory
            $purchase->deal->increment('spots_available', $purchase->quantity);

            return [
                'success' => true,
                'refund_id' => $refund->id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
