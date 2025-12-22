<?php

namespace App\Services;

use App\Models\Voucher;

class VoucherCodeService
{
    /**
     * Generate a unique voucher code
     * Format: ABCD1234EFGH5678 (16 characters, alphanumeric, uppercase)
     */
    public function generate(): string
    {
        $attempts = 0;
        $maxAttempts = 10;
        
        do {
            $code = $this->generateCode();
            $attempts++;
            
            // Check if code already exists
            $exists = Voucher::where('voucher_code', $code)->exists();
            
            if (!$exists) {
                return $code;
            }
            
        } while ($attempts < $maxAttempts);
        
        // If we still haven't found a unique code, throw exception
        throw new \Exception('Unable to generate unique voucher code after ' . $maxAttempts . ' attempts');
    }
    
    /**
     * Generate random alphanumeric code
     */
    private function generateCode(): string
    {
        // Use only uppercase letters and numbers (no confusing characters like 0, O, 1, I)
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < 16; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $code;
    }
    
    /**
     * Format code for display (ABCD-1234-EFGH-5678)
     */
    public function format(string $code): string
    {
        return implode('-', str_split($code, 4));
    }
    
    /**
     * Remove formatting from code
     */
    public function unformat(string $formattedCode): string
    {
        return str_replace('-', '', strtoupper($formattedCode));
    }
}
