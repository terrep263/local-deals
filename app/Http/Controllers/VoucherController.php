<?php

namespace App\Http\Controllers;

use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    /**
     * Show voucher verification page
     */
    public function verify()
    {
        return view('vouchers.verify');
    }

    /**
     * Check voucher status
     */
    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $result = $this->voucherService->checkVoucher($request->code);

        if (!$result['found']) {
            return back()->with('error', $result['message']);
        }

        return view('vouchers.status', [
            'voucher' => $result['voucher'],
            'is_valid' => $result['is_valid'],
        ]);
    }

    /**
     * Show redemption page (vendor only)
     */
    public function showRedemption()
    {
        $this->middleware(['auth', 'vendor']);
        return view('vendor.vouchers.redeem');
    }

    /**
     * Redeem voucher (vendor only)
     */
    public function redeem(Request $request)
    {
        $this->middleware(['auth', 'vendor']);

        $request->validate([
            'code' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $result = $this->voucherService->redeemVoucher(
            $request->code,
            Auth::user(),
            $request->notes
        );

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
