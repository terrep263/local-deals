<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIUsageTracking extends Model
{
    protected $fillable = [
        'user_id',
        'feature_type',
        'usage_date',
        'count'
    ];

    protected $casts = [
        'usage_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
