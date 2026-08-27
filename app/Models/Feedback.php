<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'user_id', 'rating', 'venue_rating', 'coordination_rating', 'technical_rating', 'hospitality_rating', 'comments', 'created_at'])]
class Feedback extends Model
{
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAverageRatingAttribute(): float
    {
        $ratings = array_filter([
            $this->rating,
            $this->venue_rating,
            $this->coordination_rating,
            $this->technical_rating,
            $this->hospitality_rating,
        ]);

        if (empty($ratings)) {
            return 0;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }

    public function getSatisfactionLabelAttribute(): string
    {
        $avg = $this->average_rating;

        return match(true) {
            $avg >= 4.5 => 'Very Satisfied',
            $avg >= 4.0 => 'Satisfied',
            $avg >= 3.0 => 'Neutral',
            $avg >= 2.0 => 'Dissatisfied',
            default => 'Very Dissatisfied',
        };
    }

    public function getStarRatingAttribute(): int
    {
        return round($this->average_rating);
    }
}