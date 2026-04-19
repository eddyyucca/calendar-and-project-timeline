<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    use HasFactory;

    public const TYPES = ['meeting', 'reminder', 'holiday', 'leave'];

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'event_date',
        'start_time',
        'end_time',
        'description',
        'is_national_holiday',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_national_holiday' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'meeting' => 'Meeting Penting',
            'holiday' => 'Hari Nasional',
            'leave' => 'Cuti Bersama',
            default => 'Pengingat',
        };
    }

    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'meeting' => '#0f5fb8',
            'holiday' => '#dc2626',
            'leave' => '#f59e0b',
            default => '#16a34a',
        };
    }
}
