<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealDailyStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'date',
        'views',
        'clicks',
        'purchases',
        'revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'clicks' => 'integer',
        'purchases' => 'integer',
        'revenue' => 'decimal:2',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}


