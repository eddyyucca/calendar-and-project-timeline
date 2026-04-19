<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_activity_id',
        'user_id',
        'progress',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(DailyActivity::class, 'daily_activity_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
