<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Listings;
use App\Models\DealPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Create Stripe Checkout session and redirect to payment
     */
    public function checkout(Listings $deal)
    {
        // Validate deal is available
        if ($deal->status !== '1') {
            return back()->with('error', 'This deal is no longer available.');
        }
        
        // Check if deal is still active and not expired
        if (!$deal->deal_price || !$deal->deal_expires_at || !$deal->deal_expires_at->isFuture()) {
            return back()->with('error', 'This deal has expired.');
        }
        
        // Check inventory
        $dealsRemaining = max(0, ($deal->deal_quantity_total ?? 0) - ($deal->deal_quantity_sold ?? 0));
        if ($dealsRemaining <= 0) {
            return back()->with('error', 'This deal is sold out.');
        }
        
        // Get vendor information
        $vendor = $deal->user;
        if (!$vendor || !$vendor->stripe_connect_account_id) {
            return back()->with('error', 'This vendor cannot accept payments at this time.');
        }
        
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            // Create Stripe Checkout Session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $deal->title,
                            'description' => $vendor->business_name ?? 'Local Deals',
                            'images' => $deal->featured_image ? [asset('upload/listings/'.$deal->featured_image.'-b.jpg')] : ['https://via.placeholder.com/300'],
                        ],
                        'unit_amount' => (int)($deal->deal_price * 100), // Stripe expects cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('customer.purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('customer.purchase.cancel'),
                'client_reference_id' => auth()->id() . '_' . $deal->id, // Track this purchase
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'listing_id' => $deal->id,
                    'user_id' => auth()->id(),
                    'vendor_id' => $vendor->id,
                ],
                // IMPORTANT: Use Stripe Connect to pay vendor directly
                'payment_intent_data' => [
                    'application_fee_amount' => 0, // Platform takes no fee (vendor pays subscription)
                    'transfer_data' => [
                        'destination' => $vendor->stripe_connect_account_id,
                    ],
                ],
            ]);
            
            // Create purchase record with pending status
            $purchase = DealPurchase::create([
                'deal_id' => $deal->id,
                'user_id' => auth()->id(),
                'vendor_profile_id' => $vendor->id,
                'purchase_amount' => $deal->deal_price,
                'stripe_checkout_session_id' => $session->id,
                'status' => 'pending',
                'consumer_email' => auth()->user()->email,
                'consumer_name' => auth()->user()->name
            ]);
            
            Log::info('Purchase initiated', [
                'purchase_id' => $purchase->id,
                'deal_id' => $deal->id,
                'user_id' => auth()->id(),
                'session_id' => $session->id
            ]);
            
            // Redirect to Stripe Checkout
            return redirect($session->url);
            
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Error', [
                'deal_id' => $deal->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Unable to process payment. Please try again or contact support.');
        }
    }
    
    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        
        return view('customer.purchase.success', [
            'sessionId' => $sessionId
        ]);
    }
    
    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        return view('customer.purchase.cancel');
    }
}
