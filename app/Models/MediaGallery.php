<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;

#[Fillable(['event_id', 'uploaded_by', 'file_type', 'file_path', 'caption', 'is_approved'])]
class MediaGallery extends Model
{
    protected $table = 'media_gallery';

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'event_id');
    }

    public function savedMedia(): HasMany
    {
        return $this->hasMany(SavedMedia::class, 'media_id');
    }
}