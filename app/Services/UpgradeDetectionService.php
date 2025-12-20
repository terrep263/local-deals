<?php

namespace App\Services;

use App\Models\User;
use App\Models\UpgradeSuggestion;
use App\Models\PackageFeature;
use App\Models\VendorMonthlyStat;
use Illuminate\Support\Facades\Log;

class UpgradeDetectionService
{
    protected $commissionService;
    
    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }
    
    /**
     * Check for upgrade opportunities for a vendor
     */
    public function checkForUpgradeOpportunities(int $userId): ?UpgradeSuggestion
    {
        $user = User::find($userId);
        if (!$user) {
            return null;
        }
        
        $subscription = $user->activeSubscription;
        $currentTier = $subscription ? $subscription->package_tier : 'starter';
        $packageFeatures = $subscription 
            ? $subscription->packageFeature() 
            : PackageFeature::getByTier('starter');
        
        if (!$packageFeatures) {
            return null;
        }
        
        // Get current month stats
        $stats = $this->commissionService->getMonthlyStats($userId);
        
        // Check volume limits
        $currentVoucherCount = $this->commissionService->getCurrentMonthVoucherCount($userId);
        $voucherLimit = $packageFeatures->monthly_voucher_limit;
        $hittingVolumeLimit = $voucherLimit && $currentVoucherCount >= $voucherLimit;
        
        // Check cost savings opportunities
        $tiers = ['starter', 'basic', 'pro', 'enterprise'];
        $currentTierIndex = array_search($currentTier, $tiers);
        
        $bestUpgrade = null;
        $maxSavings = 0;
        $upgradeReason = 'cost_savings';
        
        // Check each higher tier
        for ($i = $currentTierIndex + 1; $i < count($tiers); $i++) {
            $targetTier = $tiers[$i];
            $targetFeatures = PackageFeature::getByTier($targetTier);
            
            if (!$targetFeatures) {
                continue;
            }
            
            $savings = $this->getSavingsCalculation($userId, $targetTier);
            
            // Check if upgrade makes sense
            if ($this->shouldSuggestUpgrade($userId, $targetTier, $savings, $hittingVolumeLimit)) {
                if ($savings['monthly_savings'] > $maxSavings) {
                    $maxSavings = $savings['monthly_savings'];
                    $bestUpgrade = [
                        'tier' => $targetTier,
                        'savings' => $savings,
                    ];
                }
            }
        }
        
        // Determine reason
        if ($hittingVolumeLimit && $bestUpgrade) {
            $upgradeReason = 'both';
        } elseif ($hittingVolumeLimit) {
            // Find next tier that increases limit
            for ($i = $currentTierIndex + 1; $i < count($tiers); $i++) {
                $targetTier = $tiers[$i];
                $targetFeatures = PackageFeature::getByTier($targetTier);
                if ($targetFeatures && (!$targetFeatures->monthly_voucher_limit || $targetFeatures->monthly_voucher_limit > $voucherLimit)) {
                    $savings = $this->getSavingsCalculation($userId, $targetTier);
                    $bestUpgrade = [
                        'tier' => $targetTier,
                        'savings' => $savings,
                    ];
                    $upgradeReason = 'volume_limit';
                    break;
                }
            }
        }
        
        if (!$bestUpgrade) {
            return null;
        }
        
        // Check if suggestion already exists and is active
        $existingSuggestion = $this->getActiveUpgradeSuggestion($userId);
        if ($existingSuggestion && $existingSuggestion->to_tier === $bestUpgrade['tier']) {
            return $existingSuggestion;
        }
        
        // Create new suggestion
        $suggestion = UpgradeSuggestion::create([
            'user_id' => $userId,
            'from_tier' => $currentTier,
            'to_tier' => $bestUpgrade['tier'],
            'reason' => $upgradeReason,
            'current_monthly_cost' => $stats->total_cost,
            'suggested_monthly_cost' => $bestUpgrade['savings']['target_cost'],
            'monthly_savings' => $bestUpgrade['savings']['monthly_savings'],
        ]);
        
        Log::info('Upgrade suggestion created', [
            'user_id' => $userId,
            'from_tier' => $currentTier,
            'to_tier' => $bestUpgrade['tier'],
            'savings' => $bestUpgrade['savings']['monthly_savings'],
        ]);
        
        return $suggestion;
    }
    
    /**
     * Check if upgrade should be suggested
     */
    public function shouldSuggestUpgrade(int $userId, string $toTier, array $savings, bool $hittingVolumeLimit): bool
    {
        // Always suggest if hitting volume limit
        if ($hittingVolumeLimit) {
            return true;
        }
        
        // Suggest if savings > $50/month
        if ($savings['monthly_savings'] >= 50.00) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get savings calculation for a target tier
     */
    public function getSavingsCalculation(int $userId, string $targetTier): array
    {
        $stats = $this->commissionService->getMonthlyStats($userId);
        $targetFeatures = PackageFeature::getByTier($targetTier);
        
        if (!$targetFeatures) {
            return [
                'current_cost' => $stats->total_cost,
                'target_cost' => 0,
                'monthly_savings' => 0,
                'annual_savings' => 0,
            ];
        }
        
        $currentCost = $stats->total_cost;
        
        // Calculate target cost: base fee + commissions at new rate
        $targetBaseFee = $targetFeatures->monthly_price;
        $targetCommissionRate = $targetFeatures->commission_rate;
        $targetCommissionAmount = $stats->gross_sales * ($targetCommissionRate / 100);
        $targetCost = $targetBaseFee + $targetCommissionAmount;
        
        $monthlySavings = $currentCost - $targetCost;
        $annualSavings = $monthlySavings * 12;
        
        return [
            'current_cost' => $currentCost,
            'target_cost' => $targetCost,
            'monthly_savings' => $monthlySavings,
            'annual_savings' => $annualSavings,
            'current_base_fee' => $stats->base_subscription_fee,
            'current_commissions' => $stats->total_commissions,
            'target_base_fee' => $targetBaseFee,
            'target_commission_rate' => $targetCommissionRate,
            'target_commission_amount' => $targetCommissionAmount,
        ];
    }
    
    /**
     * Get active upgrade suggestion for user
     */
    public function getActiveUpgradeSuggestion(int $userId): ?UpgradeSuggestion
    {
        return UpgradeSuggestion::where('user_id', $userId)
            ->whereNull('dismissed_at')
            ->whereNull('converted_at')
            ->latest()
            ->first();
    }
}


