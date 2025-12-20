<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpgradeSuggestion extends Model
{
    protected $table = 'upgrade_suggestions';

    protected $fillable = [
        'user_id',
        'from_tier',
        'to_tier',
        'reason',
        'current_monthly_cost',
        'suggested_monthly_cost',
        'monthly_savings',
        'shown_at',
        'dismissed_at',
        'converted_at',
    ];

    protected $casts = [
        'current_monthly_cost' => 'decimal:2',
        'suggested_monthly_cost' => 'decimal:2',
        'monthly_savings' => 'decimal:2',
        'shown_at' => 'datetime',
        'dismissed_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->dismissed_at === null && $this->converted_at === null;
    }

    public function dismiss(): void
    {
        $this->update(['dismissed_at' => now()]);
    }

    public function markAsConverted(): void
    {
        $this->update(['converted_at' => now()]);
    }

    public function markAsShown(): void
    {
        if (!$this->shown_at) {
            $this->update(['shown_at' => now()]);
        }
    }
}


