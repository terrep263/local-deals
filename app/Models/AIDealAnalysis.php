<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIDealAnalysis extends Model
{
    protected $fillable = [
        'deal_id',
        'user_id',
        'title',
        'description',
        'original_price',
        'sale_price',
        'category_id',
        'title_score',
        'description_score',
        'pricing_score',
        'overall_score',
        'suggestions',
        'improved_title',
        'improved_description',
        'ai_model',
        'tokens_used',
        'processing_time_ms',
        'was_accepted'
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'suggestions' => 'array',
        'was_accepted' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    /**
     * Get severity badge color
     */
    public function getSeverityColor(string $severity): string
    {
        return match($severity) {
            'critical' => 'danger',
            'important' => 'warning',
            'minor' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get score badge color based on score
     */
    public function getScoreBadgeColor(int $score): string
    {
        if ($score >= 80) return 'success';
        if ($score >= 60) return 'warning';
        return 'danger';
    }

    /**
     * Get score label
     */
    public function getScoreLabel(int $score): string
    {
        if ($score >= 90) return 'Excellent';
        if ($score >= 80) return 'Very Good';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Fair';
        return 'Needs Improvement';
    }
}
