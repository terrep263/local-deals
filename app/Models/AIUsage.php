<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIUsage extends Model
{
    use HasFactory;

    protected $table = 'ai_usage_tracking';

    protected $fillable = [
        'user_id',
        'feature',
        'tokens_used',
        'cost_estimate',
    ];

    protected $casts = [
        'tokens_used' => 'integer',
        'cost_estimate' => 'decimal:6',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


