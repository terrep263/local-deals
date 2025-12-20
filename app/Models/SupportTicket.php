<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'category', 'priority', 'status',
        'assigned_to', 'first_response_at', 'resolved_at'
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id');
    }

    public function publicMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')
            ->where('is_internal', false);
    }

    public function internalMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')
            ->where('is_internal', true);
    }

    public function isOverdue(): bool
    {
        if ($this->status === 'resolved' || $this->status === 'closed') {
            return false;
        }

        $hoursSinceCreated = now()->diffInHours($this->created_at);
        
        // SLA based on subscription tier
        $user = $this->user;
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            $slaHours = 48; // Free tier
        } else {
            $slaHours = match($subscription->package_tier) {
                'basic' => 48,
                'pro' => 24,
                'enterprise' => 2,
                default => 48
            };
        }

        return $hoursSinceCreated > $slaHours;
    }
}


