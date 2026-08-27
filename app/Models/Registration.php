<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['event_id', 'user_id', 'status', 'qr_token', 'registered_at', 'cancelled_at', 'check_in_at', 'check_out_at', 'payment_status', 'payment_amount', 'payment_method', 'discount_code', 'original_price', 'seat_number', 'special_requirements', 'notification_preferences'])]
class Registration extends Model
{
    /** @use HasFactory<RegistrationFactory> */
    use HasFactory;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'registration_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'event_id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'event_id');
    }

    public function scopeConfirmed($query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeWaitlisted($query): Builder
    {
        return $query->where('status', 'waitlisted');
    }

    public function scopeCancelled($query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCheckedIn($query): Builder
    {
        return $query->whereNotNull('check_in_at');
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isWaitlisted(): bool
    {
        return $this->status === 'waitlisted';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCheckedIn(): bool
    {
        return !is_null($this->check_in_at);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'Confirmed',
            'waitlisted' => 'Waitlisted',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function getRemainingDurationAttribute(): ?string
    {
        if (is_null($this->check_in_at) || is_null($this->event)) {
            return null;
        }

        $end = $this->event->end_time;
        $now = now();

        if ($now->gt($end)) {
            return 'Event completed';
        }

        $diff = $end->diffInMinutes($now);
        if ($diff < 60) {
            return $diff . ' minutes remaining';
        }

        $hours = floor($diff / 60);
        $minutes = $diff % 60;
        return "{$hours}h {$minutes}m remaining";
    }
}