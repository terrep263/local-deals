<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VendorProfile;
use App\Services\PricingService;

class CheckFounderInactivity extends Command
{
    protected $signature = 'founders:check-inactivity';
    protected $description = 'Check founder accounts for 2-month inactivity and revoke status if needed';
    
    public function handle(PricingService $pricingService)
    {
        $this->info('Checking founder accounts for inactivity...');
        
        $founders = VendorProfile::where('is_founder', true)->get();
        
        $revokedCount = 0;
        
        foreach ($founders as $founder) {
            if ($pricingService->shouldLoseFounderStatus($founder)) {
                $this->warn("Revoking founder status: {$founder->business_name} (#{$founder->founder_number})");
                
                $pricingService->revokeFounderStatus($founder, 'inactive');
                $revokedCount++;
            }
        }
        
        if ($revokedCount > 0) {
            $this->info("Revoked {$revokedCount} founder status(es) due to inactivity.");
        } else {
            $this->info("All founder accounts are active.");
        }
        
        return 0;
    }
}
