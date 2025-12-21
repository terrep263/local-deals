<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealPurchase;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Show purchase page for a deal
     */
    public function show(Deal $deal)
    {
        // Check if deal is available
        if ($deal->status !== 'active') {
            return redirect()->route('deals.show', $deal->slug)
                ->with('error', 'This deal is no longer available.');
        }

        if ($deal->end_date && $deal->end_date->isPast()) {
            return redirect()->route('deals.show', $deal->slug)
                ->with('error', 'This deal has expired.');
        }

        if ($deal->spots_available !== null && $deal->spots_available <= 0) {
            return redirect()->route('deals.show', $deal->slug)
                ->with('error', 'This deal is sold out.');
        }

        return view('deals.purchase', compact('deal'));
    }

    /**
     * Process purchase and create Stripe checkout session
     */
    public function checkout(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // Check deal availability
        if ($deal->spots_available !== null && $deal->spots_available < $validated['quantity']) {
            return back()->with('error', 'Not enough spots available. Only ' . $deal->spots_available . ' remaining.');
        }

        $customerData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        $result = $this->purchaseService->createCheckoutSession(
            $deal,
            $validated['quantity'],
            $customerData,
            Auth::user()
        );

        if ($result['success']) {
            return redirect($result['session_url']);
        }

        return back()->with('error', $result['error'] ?? 'Failed to create checkout session.');
    }

    /**
     * Handle successful payment callback
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('home')
                ->with('error', 'Invalid session.');
        }

        try {
            $purchase = $this->purchaseService->processSuccessfulPayment($sessionId);

            return redirect()->route('purchase.confirmation', $purchase->confirmation_code)
                ->with('success', 'Purchase completed successfully!');
        } catch (\Exception $e) {
            \Log::error('Purchase processing failed: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Failed to process your purchase. Please contact support.');
        }
    }

    /**
     * Show purchase confirmation page
     */
    public function confirmation(string $confirmationCode)
    {
        $purchase = $this->purchaseService->getPurchaseByCode($confirmationCode);

        if (!$purchase) {
            return redirect()->route('home')
                ->with('error', 'Purchase not found.');
        }

        return view('purchases.confirmation', compact('purchase'));
    }
}
