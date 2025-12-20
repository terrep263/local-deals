<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorMonthlyStat extends Model
{
    protected $table = 'vendor_monthly_stats';

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'subscription_tier',
        'vouchers_sold',
        'gross_sales',
        'base_subscription_fee',
        'total_commissions',
        'total_cost',
    ];

    protected $casts = [
        'gross_sales' => 'decimal:2',
        'base_subscription_fee' => 'decimal:2',
        'total_commissions' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrCreate(int $userId, ?int $year = null, ?int $month = null): self
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'year' => $year,
                'month' => $month,
            ],
            [
                'subscription_tier' => 'starter',
                'vouchers_sold' => 0,
                'gross_sales' => 0.00,
                'base_subscription_fee' => 0.00,
                'total_commissions' => 0.00,
                'total_cost' => 0.00,
            ]
        );
    }
}


