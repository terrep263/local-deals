<?php

namespace App\Services;

use App\Models\VendorProfile;

class PricingService
{
    /**
     * Get all available plans
     */
    public function getAllPlans(): array
    {
        return config('pricing.plans');
    }
    
    /**
     * Get public plans (visible on pricing page)
     */
    public function getPublicPlans(): array
    {
        return array_filter(
            config('pricing.plans'),
            fn($plan) => $plan['public'] ?? false
        );
    }
    
    /**
     * Get plan details by slug
     */
    public function getPlan(string $slug): ?array
    {
        return config("pricing.plans.{$slug}");
    }
    
    /**
     * Get available upgrade options for vendor
     */
    public function getUpgradeOptions(VendorProfile $vendor): array
    {
        $currentPlan = $this->getPlan($vendor->subscription_tier);
        
        if (!$currentPlan) {
            return [];
        }
        
        // Founder can upgrade to founder_upgrade or starter
        if ($vendor->isFounder()) {
            return [
                'founder_upgrade' => $this->getPlan('founder_upgrade'),
                'starter' => $this->getPlan('starter'),
            ];
        }
        
        // Founder upgrade can only go to starter
        if ($vendor->hasFounderUpgrade()) {
            return [
                'starter' => $this->getPlan('starter'),
            ];
        }
        
        // Get upgrade path from current plan
        $upgradePath = $currentPlan['rules']['upgrade_path'] ?? [];
        
        $options = [];
        foreach ($upgradePath as $planSlug) {
            $options[$planSlug] = $this->getPlan($planSlug);
        }
        
        return $options;
    }
    
    /**
     * Check if vendor can upgrade to specific plan
     */
    public function canUpgradeTo(VendorProfile $vendor, string $targetPlan): bool
    {
        $options = $this->getUpgradeOptions($vendor);
        return isset($options[$targetPlan]);
    }
    
    /**
     * Apply plan limits to vendor
     */
    public function applyPlanLimits(VendorProfile $vendor, string $planSlug): void
    {
        $plan = $this->getPlan($planSlug);
        
        if (!$plan) {
            throw new \Exception("Invalid plan: {$planSlug}");
        }
        
        $vendor->update([
            'subscription_tier' => $planSlug,
            'monthly_price' => $plan['price'],
            'active_deals_limit' => $plan['features']['active_deals'] ?? 999999,
            'monthly_voucher_limit' => $plan['features']['vouchers_per_month'] ?? 999999,
        ]);
    }
    
    /**
     * Check if founder slots available
     */
    public function founderSlotsAvailable(): int
    {
        $maxFounders = config('pricing.plans.founder.limits.max_founders');
        $currentFounders = VendorProfile::where('is_founder', true)->count();
        
        return max(0, $maxFounders - $currentFounders);
    }
    
    /**
     * Claim founder status for vendor
     */
    public function claimFounderStatus(VendorProfile $vendor): bool
    {
        if ($this->founderSlotsAvailable() <= 0) {
            return false;
        }
        
        $founderNumber = VendorProfile::where('is_founder', true)->max('founder_number') + 1;
        
        $vendor->update([
            'is_founder' => true,
            'founder_number' => $founderNumber,
            'founder_claimed_at' => now(),
            'subscription_tier' => 'founder',
        ]);
        
        $this->applyPlanLimits($vendor, 'founder');
        
        return true;
    }
    
    /**
     * Check if vendor should lose founder status (2 months inactive)
     */
    public function shouldLoseFounderStatus(VendorProfile $vendor): bool
    {
        if (!$vendor->isFounder()) {
            return false;
        }
        
        // Check if no vouchers redeemed in last 2 months
        if (!$vendor->last_voucher_redeemed_at) {
            // Check if account is older than 2 months
            return $vendor->created_at->addMonths(2)->isPast();
        }
        
        return $vendor->last_voucher_redeemed_at->addMonths(2)->isPast();
    }
    
    /**
     * Revoke founder status
     */
    public function revokeFounderStatus(VendorProfile $vendor, string $reason = 'inactive'): void
    {
        \Log::warning('Revoking founder status', [
            'vendor_id' => $vendor->id,
            'founder_number' => $vendor->founder_number,
            'reason' => $reason
        ]);
        
        $vendor->update([
            'is_founder' => false,
            'subscription_tier' => 'starter', // Force to paid plan
            'consecutive_inactive_months' => 0,
        ]);
        
        $this->applyPlanLimits($vendor, 'starter');
        
        // Send notification email
        // \Mail::to($vendor->user)->send(new \App\Mail\FounderStatusRevokedEmail($vendor, $reason));
    }
    
    /**
     * Get plan comparison data for pricing page
     */
    public function getPlanComparison(): array
    {
        $plans = $this->getPublicPlans();
        
        $comparison = [];
        
        foreach ($plans as $slug => $plan) {
            $comparison[] = [
                'slug' => $slug,
                'name' => $plan['name'],
                'price' => $plan['price'],
                'features' => [
                    'Active Deals' => $plan['features']['active_deals'] ?? '∞',
                    'Vouchers/Month' => $plan['features']['unlimited_vouchers'] 
                        ? 'Unlimited' 
                        : number_format($plan['features']['vouchers_per_month']),
                    'Top Placement' => $plan['features']['top_tier_placement'] ? 'Yes' : 'No',
                ],
                'description' => $plan['description'],
            ];
        }
        
        return $comparison;
    }
}
