<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealAIAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'score',
        'strengths',
        'weaknesses',
        'suggestions',
        'competitive_analysis',
        'analyzed_at',
    ];

    protected $casts = [
        'strengths' => 'array',
        'weaknesses' => 'array',
        'suggestions' => 'array',
        'analyzed_at' => 'datetime',
        'score' => 'integer',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function getScoreColor(): string
    {
        if ($this->score >= 90) {
            return 'success'; // Green
        } elseif ($this->score >= 75) {
            return 'info'; // Blue
        } elseif ($this->score >= 60) {
            return 'warning'; // Yellow
        } else {
            return 'danger'; // Red
        }
    }

    public function getScoreLabel(): string
    {
        if ($this->score >= 90) {
            return 'Excellent';
        } elseif ($this->score >= 75) {
            return 'Good';
        } elseif ($this->score >= 60) {
            return 'Fair';
        } else {
            return 'Needs Work';
        }
    }
}


