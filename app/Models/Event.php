<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'description', 'category', 'venue', 'event_date', 'start_time', 'end_time', 'total_seats', 'certificate_fee', 'status', 'cancellation_reason', 'registration_cutoff'])]
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'certificate_fee' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'event_id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'event_id');
    }

    public function mediaGallery(): HasMany
    {
        return $this->hasMany(MediaGallery::class, 'event_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'event_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'event_id');
    }

    public function savedMedia(): HasMany
    {
        return $this->hasMany(SavedMedia::class, 'event_id');
    }

    public function scopeApproved($query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function getSeatsAvailableAttribute(): int
    {
        return $this->total_seats - $this->seats_booked;
    }
}