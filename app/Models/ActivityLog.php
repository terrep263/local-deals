<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'user_type', 'action', 'description', 'metadata', 'ip_address', 'created_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity
     */
    public static function log($action, $description, $userId = null, $userType = null, $metadata = null, $ipAddress = null)
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'user_type' => $userType ?? (auth()->user() && auth()->user()->is_admin ? 'admin' : 'vendor'),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata ? json_encode($metadata) : null,
            'ip_address' => $ipAddress ?? request()->ip(),
            'created_at' => now(),
        ]);
    }
}


