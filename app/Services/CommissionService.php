<?php

namespace App\Services;

use App\Models\DealPurchase;
use App\Models\VendorCommission;
use App\Models\VendorMonthlyStat;
use App\Models\User;
use App\Models\PackageFeature;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionService
{
    /**
     * Calculate and record commission for a purchase
     */
    public function calculateCommission(DealPurchase $purchase): VendorCommission
    {
        $deal = $purchase->deal;
        $vendor = $deal->vendor;
        
        // Get vendor's current subscription tier
        $subscription = $vendor->activeSubscription;
        $packageFeatures = $subscription 
            ? $subscription->packageFeature() 
            : PackageFeature::getByTier('starter');
        
        if (!$packageFeatures) {
            // Default to starter tier if no subscription
            $packageFeatures = PackageFeature::getByTier('starter');
            if (!$packageFeatures) {
                // Create default if doesn't exist
                $packageFeatures = $this->createDefaultStarterTier();
            }
        }
        
        $commissionRate = $packageFeatures->commission_rate ?? 15.00;
        $grossSaleAmount = $purchase->purchase_amount;
        $commissionAmount = $grossSaleAmount * ($commissionRate / 100);
        $vendorPayout = $grossSaleAmount - $commissionAmount;
        
        // Create commission record
        $commission = VendorCommission::create([
            'user_id' => $vendor->id,
            'order_id' => $purchase->id, // Using purchase ID as order ID
            'deal_id' => $deal->id,
            'gross_sale_amount' => $grossSaleAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'vendor_payout' => $vendorPayout,
            'status' => 'pending',
        ]);
        
        Log::info('Commission calculated', [
            'purchase_id' => $purchase->id,
            'vendor_id' => $vendor->id,
            'gross_sale' => $grossSaleAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
        ]);
        
        return $commission;
    }
    
    /**
     * Get total commissions for a user in a given month
     */
    public function getMonthlyCommissionTotal(int $userId, ?int $year = null, ?int $month = null): float
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        return VendorCommission::where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', '!=', 'refunded')
            ->sum('commission_amount') ?? 0.00;
    }
    
    /**
     * Get or calculate monthly stats for a vendor
     */
    public function getMonthlyStats(int $userId, ?int $year = null, ?int $month = null): VendorMonthlyStat
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        $stats = VendorMonthlyStat::getOrCreate($userId, $year, $month);
        
        // Get subscription info
        $user = User::find($userId);
        $subscription = $user->activeSubscription;
        $packageFeatures = $subscription 
            ? $subscription->packageFeature() 
            : PackageFeature::getByTier('starter');
        
        $tier = $subscription ? $subscription->package_tier : 'starter';
        $baseFee = $packageFeatures ? $packageFeatures->monthly_price : 0.00;
        
        // Calculate vouchers sold
        $vouchersSold = DealPurchase::whereHas('deal', function($q) use ($userId) {
                $q->where('vendor_id', $userId);
            })
            ->whereYear('purchase_date', $year)
            ->whereMonth('purchase_date', $month)
            ->count();
        
        // Calculate gross sales
        $grossSales = DealPurchase::whereHas('deal', function($q) use ($userId) {
                $q->where('vendor_id', $userId);
            })
            ->whereYear('purchase_date', $year)
            ->whereMonth('purchase_date', $month)
            ->sum('purchase_amount') ?? 0.00;
        
        // Calculate total commissions
        $totalCommissions = $this->getMonthlyCommissionTotal($userId, $year, $month);
        
        // Calculate total cost
        $totalCost = $baseFee + $totalCommissions;
        
        // Update stats
        $stats->update([
            'subscription_tier' => $tier,
            'vouchers_sold' => $vouchersSold,
            'gross_sales' => $grossSales,
            'base_subscription_fee' => $baseFee,
            'total_commissions' => $totalCommissions,
            'total_cost' => $totalCost,
        ]);
        
        return $stats;
    }
    
    /**
     * Get current month voucher count for a vendor
     */
    public function getCurrentMonthVoucherCount(int $userId): int
    {
        return DealPurchase::whereHas('deal', function($q) use ($userId) {
                $q->where('vendor_id', $userId);
            })
            ->whereYear('purchase_date', now()->year)
            ->whereMonth('purchase_date', now()->month)
            ->count();
    }
    
    /**
     * Create default starter tier if it doesn't exist
     */
    private function createDefaultStarterTier(): PackageFeature
    {
        return PackageFeature::create([
            'package_tier' => 'starter',
            'simultaneous_deals' => 1,
            'inventory_cap_per_deal' => -1,
            'ai_scoring_enabled' => false,
            'analytics_access' => false,
            'priority_placement' => false,
            'featured_placement' => false,
            'api_access' => false,
            'white_label' => false,
            'custom_branding' => false,
            'auto_approval' => false,
            'support_level' => 'community',
            'monthly_price' => 0.00,
            'commission_rate' => 15.00,
            'monthly_voucher_limit' => 50,
            'monthly_deal_limit' => 1,
        ]);
    }
}


