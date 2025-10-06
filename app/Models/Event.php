<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'qr_token',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->qr_token)) {
                $event->qr_token = $event->generateQrToken();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function checkedInAttendances(): HasMany
    {
        return $this->attendances()->whereNotNull('used_at');
    }

    public function pendingAttendances(): HasMany
    {
        return $this->attendances()->whereNull('used_at');
    }

    public function generateQrToken(): string
    {
        return 'event_' . bin2hex(random_bytes(16));
    }

    public function getTotalAttendeesAttribute(): int
    {
        return $this->attendances()->count();
    }

    public function getCheckedInCountAttribute(): int
    {
        return $this->checkedInAttendances()->count();
    }
}
