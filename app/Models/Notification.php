<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;

#[Fillable(['user_id', 'type', 'icon', 'title', 'body', 'href', 'is_read'])]
class Notification extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUnread($query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query): Builder
    {
        return $query->where('is_read', true);
    }
}