<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['registration_id', 'attended', 'check_in_at', 'check_out_at', 'marked_by', 'qr_scanned_at'])]
class Attendance extends Model
{
    protected $table = 'attendance';

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function isPresent(): bool
    {
        return $this->attended ?? false;
    }

    public function checkedIn(): bool
    {
        return !is_null($this->check_in_at);
    }

    public function checkedOut(): bool
    {
        return !is_null($this->check_out_at);
    }

    public function getDurationAttribute(): ?string
    {
        if (is_null($this->check_in_at) || is_null($this->check_out_at)) {
            return null;
        }

        $diff = $this->check_out_at->diffInMinutes($this->check_in_at);

        if ($diff < 0) {
            return null;
        }

        if ($diff < 60) {
            return $diff . ' minutes';
        }

        $hours = floor($diff / 60);
        $minutes = $diff % 60;
        return "{$hours}h {$minutes}m";
    }

    public function getCheckInTimeAttribute(): ?string
    {
        return $this->check_in_at?->format('h:i A');
    }

    public function getCheckOutTimeAttribute(): ?string
    {
        return $this->check_out_at?->format('h:i A');
    }
}