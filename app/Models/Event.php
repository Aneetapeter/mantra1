<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'title',
        'type',
        'start_datetime',
        'end_datetime',
        'description',
        'color',
        'reminder',
        'is_recurring',
        'recurrence_rule',
        'parent_event_id',
        'status',
        'priority',
        'reminder_sent_at'
    ];

    protected $casts = [
        // 'date' removed - using custom accessor/mutator
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    /**
     * Format date as DD-MM-YYYY for frontend
     */
    public function getDateAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format('d-m-Y');
        }
        return $value;
    }

    /**
     * Store date in YYYY-MM-DD format for database
     */
    public function setDateAttribute($value)
    {
        if ($value) {
            // Parse various formats
            try {
                $this->attributes['date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->attributes['date'] = $value;
            }
        }
    }

    /**
     * Default colors for event types
     */
    public static $typeColors = [
        'study' => '#5C7CFA',    // Blue
        'exam' => '#E53935',     // Red
        'meeting' => '#8E6CEF',  // Purple
        'birthday' => '#FBC02D', // Yellow
        'review' => '#FBC02D',   // Yellow
        'completed' => '#4CAF50', // Green
        'cancelled' => '#8A8F98', // Grey
    ];

    /**
     * Get the color for this event (custom or type-based)
     */
    public function getDisplayColorAttribute(): string
    {
        return $this->color ?? (self::$typeColors[$this->type] ?? '#5C7CFA');
    }

    /**
     * Parent event for recurring instances
     */
    public function parentEvent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'parent_event_id');
    }

    /**
     * Child recurring instances
     */
    public function recurringInstances(): HasMany
    {
        return $this->hasMany(Event::class, 'parent_event_id');
    }

    /**
     * User who owns this event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
