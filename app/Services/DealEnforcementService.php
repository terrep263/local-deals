<?php

namespace App\Services;

use App\Models\User;
use App\Models\PackageFeature;
use Illuminate\Support\Facades\Log;

/**
 * Service to enforce deal creation limits based on subscription tier
 * This will be used in Module 2 when DealController is created
 */
class DealEnforcementService
{
    /**
     * Check if user can create a new deal (simultaneous deals limit)
     * 
     * @param User $user
     * @param int $currentActiveDealsCount
     * @return array ['allowed' => bool, 'message' => string, 'current' => int, 'limit' => int]
     */
    public function checkSimultaneousDealsLimit(User $user, int $currentActiveDealsCount): array
    {
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            $features = PackageFeature::getByTier('starter');
        } else {
            $features = $subscription->packageFeature();
        }
        
        if (!$features) {
            return [
                'allowed' => false,
                'message' => 'Unable to verify subscription features.',
                'current' => $currentActiveDealsCount,
                'limit' => 0,
            ];
        }
        
        $limit = $features->simultaneous_deals;
        
        // Unlimited
        if ($limit == -1) {
            return [
                'allowed' => true,
                'message' => 'Unlimited deals allowed.',
                'current' => $currentActiveDealsCount,
                'limit' => -1,
            ];
        }
        
        // Check limit
        if ($currentActiveDealsCount >= $limit) {
            $tierName = ucfirst($subscription->package_tier ?? 'starter');
            $nextTier = $this->getNextTier($subscription->package_tier ?? 'starter');
            
            return [
                'allowed' => false,
                'message' => "You have {$currentActiveDealsCount} active deals (limit: {$limit}). Close an existing deal or upgrade to {$nextTier} for more simultaneous deals.",
                'current' => $currentActiveDealsCount,
                'limit' => $limit,
                'upgrade_tier' => $nextTier,
            ];
        }
        
        return [
            'allowed' => true,
            'message' => "Active deals: {$currentActiveDealsCount} of {$limit}",
            'current' => $currentActiveDealsCount,
            'limit' => $limit,
        ];
    }
    
    /**
     * Check if inventory amount is within limit for user's tier
     * 
     * @param User $user
     * @param int $requestedInventory
     * @return array ['allowed' => bool, 'message' => string, 'requested' => int, 'limit' => int]
     */
    public function checkInventoryCap(User $user, int $requestedInventory): array
    {
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            $features = PackageFeature::getByTier('starter');
        } else {
            $features = $subscription->packageFeature();
        }
        
        if (!$features) {
            return [
                'allowed' => false,
                'message' => 'Unable to verify subscription features.',
                'requested' => $requestedInventory,
                'limit' => 0,
            ];
        }
        
        $limit = $features->inventory_cap_per_deal;
        
        // Unlimited
        if ($limit == -1) {
            return [
                'allowed' => true,
                'message' => 'Unlimited inventory allowed.',
                'requested' => $requestedInventory,
                'limit' => -1,
            ];
        }
        
        // Check limit
        if ($requestedInventory > $limit) {
            $tierName = ucfirst($subscription->package_tier ?? 'starter');
            $nextTier = $this->getNextTier($subscription->package_tier ?? 'starter');
            
            return [
                'allowed' => false,
                'message' => "Your plan allows max {$limit} spots per deal. You requested {$requestedInventory}. Upgrade to {$nextTier} for unlimited inventory.",
                'requested' => $requestedInventory,
                'limit' => $limit,
                'upgrade_tier' => $nextTier,
            ];
        }
        
        return [
            'allowed' => true,
            'message' => "Deal inventory: {$requestedInventory} of {$limit} max",
            'requested' => $requestedInventory,
            'limit' => $limit,
        ];
    }
    
    /**
     * Get the next tier name for upgrade suggestions
     */
    private function getNextTier(string $currentTier): string
    {
        $tiers = [
            'starter' => 'Basic',
            'basic' => 'Pro',
            'pro' => 'Enterprise',
            'enterprise' => 'Enterprise',
        ];
        
        return $tiers[$currentTier] ?? 'Pro';
    }
}


