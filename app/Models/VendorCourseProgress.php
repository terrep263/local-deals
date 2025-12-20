<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCourseProgress extends Model
{
    protected $table = 'vendor_course_progress';

    protected $fillable = [
        'user_id',
        'course_number',
        'course_title',
        'started_at',
        'completed_at',
        'time_spent_seconds',
        'passed',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean',
    ];

    /**
     * Get the user that owns this course progress
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if course is completed
     */
    public function isCompleted(): bool
    {
        return $this->passed && $this->completed_at !== null;
    }

    /**
     * Mark course as started
     */
    public function markStarted(): void
    {
        if (!$this->started_at) {
            $this->started_at = now();
            $this->save();
        }
    }

    /**
     * Mark course as completed
     */
    public function markCompleted(int $timeSpentSeconds = 0): void
    {
        $this->passed = true;
        $this->completed_at = now();
        $this->time_spent_seconds = $timeSpentSeconds;
        $this->save();
    }
}


