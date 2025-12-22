<?php

namespace App\Services;

use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VoucherPDFService
{
    /**
     * Generate PDF voucher
     * 
     * @param Voucher $voucher
     * @return string - Path to saved PDF (relative to storage)
     */
    public function generate(Voucher $voucher): string
    {
        // Prepare data for PDF
        $data = [
            'voucher' => $voucher,
            'deal' => $voucher->deal,
            'customer' => $voucher->customer,
            'vendor' => $voucher->deal->vendor,
            'qr_code_url' => public_path('storage/' . $voucher->qr_code_path),
            'formatted_code' => $voucher->getFormattedCode()
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('vouchers.pdf-template', $data)
            ->setPaper('letter', 'portrait')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);
        
        // Create filename
        $filename = 'vouchers/' . $voucher->voucher_code . '.pdf';
        
        // Ensure directory exists
        Storage::disk('public')->makeDirectory('vouchers');
        
        // Save PDF
        Storage::disk('public')->put($filename, $pdf->output());
        
        return $filename;
    }
    
    /**
     * Delete PDF file
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
}
