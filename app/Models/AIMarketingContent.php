<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIMarketingContent extends Model
{
    use HasFactory;

    protected $table = 'ai_marketing_content';

    protected $fillable = [
        'user_id',
        'deal_id',
        'content_type',
        'platform',
        'subject_lines',
        'body_content',
        'headlines',
        'descriptions',
        'headline',
        'subheadline',
        'body_text',
        'fine_print',
        'post_content',
        'hashtags',
        'call_to_action',
        'tokens_used',
        'processing_time_ms',
        'is_used',
        'used_at',
        'rating',
    ];

    protected $casts = [
        'subject_lines' => 'array',
        'headlines' => 'array',
        'descriptions' => 'array',
        'hashtags' => 'array',
        'tokens_used' => 'integer',
        'processing_time_ms' => 'integer',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this content
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the deal this content is for
     */
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    /**
     * Get content type label
     */
    public function getContentTypeLabel(): string
    {
        return match($this->content_type) {
            'email' => 'Email Campaign',
            'social' => 'Social Media',
            'ads' => 'Ad Copy',
            'signage' => 'In-Store Signage',
            default => ucfirst($this->content_type)
        };
    }

    /**
     * Get content type icon
     */
    public function getContentTypeIcon(): string
    {
        return match($this->content_type) {
            'email' => 'envelope',
            'social' => 'share-2',
            'ads' => 'megaphone',
            'signage' => 'clipboard',
            default => 'file'
        };
    }

    /**
     * Get platform label if applicable
     */
    public function getPlatformLabel(): string
    {
        return match($this->platform) {
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'twitter' => 'Twitter/X',
            'google_ads' => 'Google Ads',
            'facebook_ads' => 'Facebook Ads',
            null => 'N/A',
            default => ucfirst($this->platform)
        };
    }

    /**
     * Get rating display (out of 5 stars)
     */
    public function getRatingDisplay(): string
    {
        if (is_null($this->rating)) {
            return 'Not rated';
        }
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get usage status label
     */
    public function getUsageStatus(): string
    {
        if ($this->is_used) {
            return 'Used on ' . $this->used_at?->format('M d, Y');
        }
        return 'Not yet used';
    }

    /**
     * Scope: Get unused content
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope: Get by content type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('content_type', $type);
    }

    /**
     * Scope: Get by platform
     */
    public function scopeOfPlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope: Get recently created
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get highest rated
     */
    public function scopeTopRated($query)
    {
        return $query->whereNotNull('rating')
            ->orderBy('rating', 'desc');
    }
}
