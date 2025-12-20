<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DealPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id', 'consumer_email', 'consumer_name', 'purchase_amount', 'confirmation_code',
        'purchase_date', 'redeemed_at', 'vendor_notified', 'notes'
    ];

    protected $casts = [
        'purchase_amount' => 'decimal:2',
        'purchase_date' => 'datetime',
        'redeemed_at' => 'datetime',
        'vendor_notified' => 'boolean',
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

    public function isRedeemed(): bool
    {
        return $this->redeemed_at !== null;
    }

    public function markAsRedeemed(): void
    {
        $this->update(['redeemed_at' => now()]);
    }

    public static function generateConfirmationCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('confirmation_code', $code)->exists());

        return $code;
    }
}
