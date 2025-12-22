<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorEmailCampaign extends Model
{
    use HasFactory;

    protected $table = 'vendor_email_campaigns';

    protected $fillable = [
        'user_id',
        'ai_marketing_content_id',
        'subject',
        'body_html',
        'body_text',
        'recipient_email',
        'status',
        'sent_at',
        'delivered_at',
        'opened_at',
        'click_count',
        'open_count',
        'bounce_reason',
        'resend_message_id',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'click_count' => 'integer',
        'open_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this campaign
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the marketing content used
     */
    public function marketingContent(): BelongsTo
    {
        return $this->belongsTo(AIMarketingContent::class, 'ai_marketing_content_id');
    }

    /**
     * Get open rate as percentage
     */
    public function getOpenRate(): float
    {
        if ($this->open_count == 0) {
            return 0;
        }
        // Assuming we track how many times email was sent
        // For now, return open count as a metric
        return $this->open_count;
    }

    /**
     * Get click rate as percentage
     */
    public function getClickRate(): float
    {
        if ($this->click_count == 0) {
            return 0;
        }
        return $this->click_count;
    }

    /**
     * Get status label with formatting
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => '<span class="badge badge-secondary">Draft</span>',
            'scheduled' => '<span class="badge badge-info">Scheduled</span>',
            'sending' => '<span class="badge badge-warning">Sending</span>',
            'sent' => '<span class="badge badge-success">Sent</span>',
            'failed' => '<span class="badge badge-danger">Failed</span>',
            default => $this->status
        };
    }

    /**
     * Get engagement metrics
     */
    public function getEngagementMetrics(): array
    {
        return [
            'sent' => $this->status === 'sent' ? 'Yes' : 'No',
            'opens' => $this->open_count ?? 0,
            'clicks' => $this->click_count ?? 0,
            'sent_at' => $this->sent_at?->format('M d, Y H:i') ?? 'Not sent',
        ];
    }

    /**
     * Scope: Get draft campaigns
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Get sent campaigns
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Get failed campaigns
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Get campaigns from last N days
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get high engagement campaigns
     */
    public function scopeHighEngagement($query)
    {
        return $query->where('open_count', '>=', 5)
            ->orWhere('click_count', '>=', 2);
    }
}
