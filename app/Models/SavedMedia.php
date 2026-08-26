<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;

#[Fillable(['user_id', 'media_id'])]
class SavedMedia extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaGallery::class, 'media_id');
    }
}