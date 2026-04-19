<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyActivity extends Model
{
    use HasFactory;

    public const STATUSES = ['Belum Mulai', 'Berjalan', 'Tertunda', 'Selesai'];
    public const PRIORITIES = ['Rendah', 'Normal', 'Tinggi', 'Urgent'];
    public const CATEGORIES = ['Operasional', 'Administrasi', 'HRGA', 'Koordinasi', 'Improvement'];

    protected $fillable = [
        'user_id',
        'title',
        'activity_date',
        'category',
        'priority',
        'status',
        'progress',
        'description',
        'blocker',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'completed_at' => 'datetime',
            'progress' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ActivityComment::class)->latest();
    }

    public function getProgressColorAttribute(): string
    {
        return match (true) {
            $this->progress >= 100 => 'success',
            $this->progress >= 70 => 'primary',
            $this->progress >= 40 => 'info',
            $this->progress >= 15 => 'warning',
            default => 'secondary',
        };
    }
}
