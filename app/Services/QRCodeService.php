<?php

namespace App\Services;

use SimpleSoftwareIO\QR\Facades\QR;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR code for voucher
     * 
     * @param string $voucherCode - The voucher code to encode
     * @return string - Path to saved QR code (relative to storage)
     */
    public function generate(string $voucherCode): string
    {
        // Generate QR code as PNG
        $qrCode = QR::format('png')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H') // High error correction
            ->generate($voucherCode);
        
        // Create filename
        $filename = 'qr-codes/' . $voucherCode . '.png';
        
        // Ensure directory exists
        Storage::disk('public')->makeDirectory('qr-codes');
        
        // Save QR code
        Storage::disk('public')->put($filename, $qrCode);
        
        return $filename;
    }
    
    /**
     * Generate QR code with logo/branding (optional, future enhancement)
     */
    public function generateWithLogo(string $voucherCode, string $logoPath): string
    {
        $qrCode = QR::format('png')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->merge($logoPath, 0.3, true) // Merge logo at 30% size
            ->generate($voucherCode);
        
        $filename = 'qr-codes/' . $voucherCode . '.png';
        Storage::disk('public')->put($filename, $qrCode);
        
        return $filename;
    }
    
    /**
     * Delete QR code file
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
}
