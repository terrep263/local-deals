<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DealPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'user_id',
        'quantity',
        'consumer_email',
        'consumer_name',
        'customer_phone',
        'purchase_amount',
        'confirmation_code',
        'stripe_payment_intent_id',
        'stripe_checkout_session_id',
        'status',
        'voucher_codes',
        'voucher_sent_at',
        'purchase_date',
        'redeemed_at',
        'vendor_notified',
        'notes',
    ];

    protected $casts = [
        'purchase_amount' => 'decimal:2',
        'purchase_date' => 'datetime',
        'redeemed_at' => 'datetime',
        'voucher_sent_at' => 'datetime',
        'vendor_notified' => 'boolean',
        'voucher_codes' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            if (empty($purchase->confirmation_code)) {
                $purchase->confirmation_code = static::generateConfirmationCode();
            }
            if (empty($purchase->purchase_date)) {
                $purchase->purchase_date = now();
            }
        });
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function isRedeemed(): bool
    {
        return $this->redeemed_at !== null;
    }

    public function markAsRedeemed(): void
    {
        $this->update(['redeemed_at' => now()]);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->purchase_amount;
    }

    public static function generateConfirmationCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('confirmation_code', $code)->exists());

        return $code;
    }
}
