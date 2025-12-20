<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'stripe_subscription_id', 'stripe_customer_id', 'stripe_price_id',
        'package_tier', 'status', 'current_period_start', 'current_period_end',
        'cancel_at_period_end', 'trial_ends_at'
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancel_at_period_end' => 'boolean',
        'trial_ends_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function packageFeature()
    {
        return PackageFeature::getByTier($this->package_tier);
    }

    public function events(): HasMany
    {
        return $this->hasMany(SubscriptionEvent::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->cancel_at_period_end;
    }

    public function cancel(bool $immediately = false): bool
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $subscription = \Stripe\Subscription::retrieve($this->stripe_subscription_id);
            
            if ($immediately) {
                $subscription->cancel();
                $this->update(['status' => 'canceled', 'cancel_at_period_end' => false]);
            } else {
                $subscription->cancel_at_period_end = true;
                $subscription->save();
                $this->update(['cancel_at_period_end' => true]);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to cancel subscription: ' . $e->getMessage());
            return false;
        }
    }

    public function cancelImmediately(): bool
    {
        return $this->cancel(true);
    }

    public function resume(): bool
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $subscription = \Stripe\Subscription::retrieve($this->stripe_subscription_id);
            $subscription->cancel_at_period_end = false;
            $subscription->save();
            
            $this->update(['status' => 'active', 'cancel_at_period_end' => false]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to resume subscription: ' . $e->getMessage());
            return false;
        }
    }
}

