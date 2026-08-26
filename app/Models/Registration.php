<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['event_id', 'user_id', 'status', 'qr_token', 'registered_at', 'cancelled_at'])]
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
}