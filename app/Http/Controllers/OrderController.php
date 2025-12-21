<?php

namespace App\Http\Controllers;

use App\Models\DealPurchase;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->middleware('auth');
        $this->voucherService = $voucherService;
    }

    /**
     * Display user's order history
     */
    public function index()
    {
        $purchases = DealPurchase::with(['deal', 'vouchers'])
            ->where('user_id', Auth::id())
            ->orWhere('consumer_email', Auth::user()->email)
            ->orderBy('purchase_date', 'desc')
            ->paginate(10);

        return view('orders.index', compact('purchases'));
    }

    /**
     * Show order details
     */
    public function show(string $confirmationCode)
    {
        $purchase = DealPurchase::with(['deal', 'vouchers'])
            ->where('confirmation_code', $confirmationCode)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('consumer_email', Auth::user()->email);
            })
            ->firstOrFail();

        return view('orders.show', compact('purchase'));
    }

    /**
     * Download voucher PDF
     */
    public function downloadVoucher(string $voucherCode)
    {
        $result = $this->voucherService->checkVoucher($voucherCode);

        if (!$result['found']) {
            return redirect()->back()
                ->with('error', 'Voucher not found.');
        }

        $voucher = $result['voucher'];

        // Check ownership
        if ($voucher->dealPurchase->user_id !== Auth::id() && 
            $voucher->dealPurchase->consumer_email !== Auth::user()->email) {
            abort(403, 'Unauthorized');
        }

        $pdfPath = $this->voucherService->generateVoucherPDF($voucher);

        return response()->download(storage_path('app/public/' . $pdfPath));
    }

    /**
     * Download all vouchers for a purchase
     */
    public function downloadPurchaseVouchers(string $confirmationCode)
    {
        $purchase = DealPurchase::with(['vouchers', 'deal'])
            ->where('confirmation_code', $confirmationCode)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('consumer_email', Auth::user()->email);
            })
            ->firstOrFail();

        $pdfPath = $this->voucherService->generatePurchasePDF($purchase);

        return response()->download(storage_path('app/public/' . $pdfPath));
    }
}
