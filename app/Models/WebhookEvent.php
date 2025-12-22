<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $fillable = [
        'stripe_event_id',
        'type',
        'payload',
        'processed',
        'processed_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
        'processed_at' => 'datetime'
    ];

    /**
     * Mark event as processed
     */
    public function markProcessed(): void
    {
        $this->update([
            'processed' => true,
            'processed_at' => now()
        ]);
    }

    /**
     * Check if event has already been processed
     */
    public static function hasBeenProcessed(string $eventId): bool
    {
        return self::where('stripe_event_id', $eventId)
            ->where('processed', true)
            ->exists();
    }
}
