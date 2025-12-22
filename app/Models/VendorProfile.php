<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorProfile extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id', 'business_name', 'business_address', 'business_city',
        'business_state', 'business_zip', 'business_phone', 'business_category',
        'business_description', 'business_logo', 'business_hours',
        'stripe_account_id', 'stripe_connected_at', 'subscription_tier',
        'monthly_voucher_limit', 'vouchers_used_this_month',
        'billing_period_start', 'is_founder', 'onboarding_completed',
        'profile_completed', 'monthly_price', 'active_deals_limit',
        'active_deals_count', 'founder_number', 'founder_claimed_at',
        'consecutive_inactive_months', 'last_voucher_redeemed_at',
        'stripe_subscription_id', 'stripe_customer_id', 'stripe_payment_method_id',
        'subscription_started_at', 'subscription_ends_at'
    ];
    
    protected $casts = [
        'business_hours' => 'array',
        'stripe_connected_at' => 'datetime',
        'billing_period_start' => 'date',
        'is_founder' => 'boolean',
        'onboarding_completed' => 'boolean',
        'profile_completed' => 'boolean',
        'monthly_price' => 'decimal:2',
        'founder_claimed_at' => 'datetime',
        'last_voucher_redeemed_at' => 'datetime',
        'subscription_started_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function deals()
    {
        return $this->hasMany(Deal::class, 'user_id', 'user_id');
    }
    
    // Methods
    public function isFounder(): bool
    {
        return $this->is_founder;
    }
    
    public function hasFounderUpgrade(): bool
    {
        return $this->subscription_tier === 'founder_upgrade';
    }
    
    public function canClaimFounder(): bool
    {
        // Check if there are less than 25 founders
        return self::where('is_founder', true)->count() < 25;
    }
    
    public function hasUnlimitedVouchers(): bool
    {
        return in_array($this->subscription_tier, ['pro', 'enterprise']);
    }
    
    public function hasUnlimitedDeals(): bool
    {
        return $this->subscription_tier === 'enterprise';
    }
    
    public function hasReachedDealLimit(): bool
    {
        if ($this->hasUnlimitedDeals()) {
            return false;
        }
        
        return $this->active_deals_count >= $this->active_deals_limit;
    }
    
    public function canCreateDeals(): bool
    {
        return $this->stripe_account_id !== null && $this->profile_completed;
    }
    
    public function remainingVouchers(): int
    {
        return max(0, $this->monthly_voucher_limit - $this->vouchers_used_this_month);
    }
    
    public function hasReachedCapacity(): bool
    {
        if ($this->subscription_tier === 'enterprise') {
            return false; // Unlimited
        }
        return $this->vouchers_used_this_month >= $this->monthly_voucher_limit;
    }
    
    public function pauseAllDeals(): void
    {
        $this->deals()->update([
            'auto_paused' => true,
            'pause_reason' => 'capacity_reached'
        ]);
    }
    
    public function resumeAllDeals(): void
    {
        $this->deals()
            ->where('auto_paused', true)
            ->where('pause_reason', 'capacity_reached')
            ->update([
                'auto_paused' => false,
                'pause_reason' => null
            ]);
    }
    
    public function resetMonthlyCounter(): void
    {
        $this->update(['vouchers_used_this_month' => 0]);
        $this->resumeAllDeals();
    }

    public function incrementVoucherUsage(): void
    {
        $this->increment('vouchers_used_this_month');

        if ($this->hasReachedCapacity()) {
            $this->pauseAllDeals();
            \Mail::to($this->user->email)->send(new \App\Mail\CapacityReachedEmail($this));
        }
    }
    
    // Accessors
    public function getVouchersRemainingThisMonthAttribute(): int
    {
        return $this->remainingVouchers();
    }
    
    public function getCapacityPercentageAttribute(): int
    {
        if ($this->monthly_voucher_limit === 0) return 0;
        return (int) (($this->vouchers_used_this_month / $this->monthly_voucher_limit) * 100);
    }
    
    public function getIsOnboardedAttribute(): bool
    {
        return $this->onboarding_completed && $this->stripe_account_id !== null && $this->profile_completed;
    }
    
    // Scopes
    public function scopeFounders($query)
    {
        return $query->where('is_founder', true);
    }
    
    public function scopeActiveVendors($query)
    {
        return $query->where('onboarding_completed', true);
    }
    
    public function scopeNeedsOnboarding($query)
    {
        return $query->where('onboarding_completed', false);
    }
}

