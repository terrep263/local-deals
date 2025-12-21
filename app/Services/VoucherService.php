<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\DealPurchase;
use App\Models\Deal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VoucherService
{
    /**
     * Generate vouchers for a purchase
     */
    public function generateVouchers(DealPurchase $purchase): array
    {
        $vouchers = [];
        $validUntil = $this->calculateValidUntil($purchase->deal);

        for ($i = 0; $i < $purchase->quantity; $i++) {
            $voucher = Voucher::create([
                'deal_purchase_id' => $purchase->id,
                'deal_id' => $purchase->deal_id,
                'valid_until' => $validUntil,
                'status' => 'active',
            ]);

            $vouchers[] = $voucher;
        }

        // Update purchase with voucher codes
        $purchase->update([
            'voucher_codes' => collect($vouchers)->pluck('code')->toArray(),
        ]);

        return $vouchers;
    }

    /**
     * Calculate voucher expiration date
     */
    private function calculateValidUntil(Deal $deal): \DateTime
    {
        // Voucher valid until deal end date or 1 year from now, whichever is sooner
        $dealEndDate = $deal->end_date;
        $oneYearFromNow = now()->addYear();

        return $dealEndDate && $dealEndDate->lt($oneYearFromNow) 
            ? $dealEndDate 
            : $oneYearFromNow;
    }

    /**
     * Generate PDF voucher
     */
    public function generateVoucherPDF(Voucher $voucher): string
    {
        $voucher->load(['deal', 'dealPurchase']);

        $pdf = Pdf::loadView('vouchers.pdf', [
            'voucher' => $voucher,
            'deal' => $voucher->deal,
            'purchase' => $voucher->dealPurchase,
        ]);

        $filename = 'voucher-' . $voucher->code . '.pdf';
        $path = 'vouchers/pdfs/' . $filename;

        Storage::put('public/' . $path, $pdf->output());

        return $path;
    }

    /**
     * Generate combined PDF for all vouchers in a purchase
     */
    public function generatePurchasePDF(DealPurchase $purchase): string
    {
        $purchase->load(['vouchers', 'deal']);

        $pdf = Pdf::loadView('vouchers.purchase-pdf', [
            'purchase' => $purchase,
            'vouchers' => $purchase->vouchers,
            'deal' => $purchase->deal,
        ]);

        $filename = 'purchase-' . $purchase->confirmation_code . '.pdf';
        $path = 'vouchers/pdfs/' . $filename;

        Storage::put('public/' . $path, $pdf->output());

        return $path;
    }

    /**
     * Validate and redeem voucher
     */
    public function redeemVoucher(string $code, $user = null, string $notes = null): array
    {
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Voucher not found.',
            ];
        }

        if (!$voucher->isValid()) {
            return [
                'success' => false,
                'message' => 'This voucher is not valid. Status: ' . $voucher->status,
            ];
        }

        if ($voucher->redeem($user, $notes)) {
            return [
                'success' => true,
                'message' => 'Voucher redeemed successfully!',
                'voucher' => $voucher,
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to redeem voucher.',
        ];
    }

    /**
     * Check voucher status
     */
    public function checkVoucher(string $code): array
    {
        $voucher = Voucher::with(['deal', 'dealPurchase'])
            ->where('code', $code)
            ->first();

        if (!$voucher) {
            return [
                'found' => false,
                'message' => 'Voucher not found.',
            ];
        }

        return [
            'found' => true,
            'voucher' => $voucher,
            'is_valid' => $voucher->isValid(),
            'status' => $voucher->status,
            'valid_until' => $voucher->valid_until,
            'deal' => $voucher->deal,
        ];
    }

    /**
     * Expire old vouchers
     */
    public function expireOldVouchers(): int
    {
        return Voucher::where('status', 'active')
            ->where('valid_until', '<', now())
            ->update(['status' => 'expired']);
    }
}
