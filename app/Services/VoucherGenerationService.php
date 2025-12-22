<?php

namespace App\Services;

use App\Models\DealPurchase;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherGenerationService
{
    private VoucherCodeService $codeService;
    private QRCodeService $qrService;
    private VoucherPDFService $pdfService;
    
    public function __construct(
        VoucherCodeService $codeService,
        QRCodeService $qrService,
        VoucherPDFService $pdfService
    ) {
        $this->codeService = $codeService;
        $this->qrService = $qrService;
        $this->pdfService = $pdfService;
    }
    
    /**
     * Generate complete voucher for a purchase
     * 
     * @param DealPurchase $purchase
     * @return Voucher
     * @throws \Exception
     */
    public function generate(DealPurchase $purchase): Voucher
    {
        Log::info('Starting voucher generation', [
            'purchase_id' => $purchase->id,
            'deal_id' => $purchase->deal_id,
            'user_id' => $purchase->user_id
        ]);
        
        // Use database transaction for atomicity
        return DB::transaction(function () use ($purchase) {
            
            // Step 1: Generate unique voucher code
            $voucherCode = $this->codeService->generate();
            
            Log::info('Voucher code generated', [
                'code' => $voucherCode
            ]);
            
            // Step 2: Generate QR code
            $qrPath = $this->qrService->generate($voucherCode);
            
            Log::info('QR code generated', [
                'path' => $qrPath
            ]);
            
            // Step 3: Create voucher record
            $voucher = Voucher::create([
                'deal_id' => $purchase->deal_id,
                'user_id' => $purchase->user_id,
                'deal_purchase_id' => $purchase->id,
                'voucher_code' => $voucherCode,
                'qr_code_path' => $qrPath,
                'purchase_date' => now(),
                'expiration_date' => $this->calculateExpirationDate($purchase->deal),
                'status' => 'active'
            ]);
            
            Log::info('Voucher record created', [
                'voucher_id' => $voucher->id
            ]);
            
            // Step 4: Generate PDF
            $pdfPath = $this->pdfService->generate($voucher);
            
            // Update voucher with PDF path
            $voucher->update(['pdf_path' => $pdfPath]);
            
            Log::info('PDF generated', [
                'path' => $pdfPath
            ]);
            
            // Step 5: Link voucher to purchase
            $purchase->update(['voucher_id' => $voucher->id]);
            
            Log::info('Voucher generation completed', [
                'voucher_id' => $voucher->id,
                'purchase_id' => $purchase->id
            ]);
            
            return $voucher;
        });
    }
    
    /**
     * Calculate voucher expiration date based on deal settings
     */
    private function calculateExpirationDate($deal): \Carbon\Carbon
    {
        // Use deal's end date if exists
        if ($deal->end_date) {
            return $deal->end_date;
        }
        
        // Otherwise, default to 90 days from now
        return now()->addDays(90);
    }
    
    /**
     * Delete voucher and all associated files
     */
    public function deleteVoucher(Voucher $voucher): bool
    {
        try {
            // Delete QR code
            if ($voucher->qr_code_path) {
                $this->qrService->delete($voucher->qr_code_path);
            }
            
            // Delete PDF
            if ($voucher->pdf_path) {
                $this->pdfService->delete($voucher->pdf_path);
            }
            
            // Delete voucher record
            $voucher->delete();
            
            Log::info('Voucher deleted', [
                'voucher_id' => $voucher->id
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error deleting voucher', [
                'voucher_id' => $voucher->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
