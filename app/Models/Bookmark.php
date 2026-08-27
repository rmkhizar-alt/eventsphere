<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'event_id', 'created_at'])]
class Bookmark extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function isRecent(): bool
    {
        return $this->created_at->diffInDays(now()) <= 7;
    }

    public function getEventDetailsAttribute()
    {
        return [
            'title' => $this->event->title,
            'date' => $this->event->event_date,
            'category' => $this->event->category,
            'seats_available' => $this->event->seats_available,
        ];
    }
}