<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorCommission extends Model
{
    protected $table = 'vendor_commissions';

    protected $fillable = [
        'user_id',
        'order_id',
        'deal_id',
        'gross_sale_amount',
        'commission_rate',
        'commission_amount',
        'vendor_payout',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'gross_sale_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_payout' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function markAsRefunded(): void
    {
        $this->update(['status' => 'refunded']);
    }
}


