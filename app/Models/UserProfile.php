<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'department', 'enrolment_no', 'avatar_path', 'bio'])]
class UserProfile extends Model
{
    protected $table = 'user_profiles';

    public function user(): HasOne
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}