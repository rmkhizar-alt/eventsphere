<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'department', 'enrolment_no', 'avatar_path', 'bio', 'phone', 'address', 'website', 'github', 'linkedin', 'portfolio', 'timezone', 'theme_preference', 'email_notifications', 'sms_notifications'])]
class UserProfile extends Model
{
    protected $table = 'user_profiles';

    public function user(): HasOne
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFullBioAttribute(): string
    {
        return $this->bio ?? 'No bio provided';
    }

    public function getSocialLinksAttribute(): array
    {
        $links = [];

        if (!empty($this->github)) {
            $links['github'] = 'https://github.com/' . $this->github;
        }

        if (!empty($this->linkedin)) {
            $links['linkedin'] = 'https://linkedin.com/in/' . $this->linkedin;
        }

        if (!empty($this->website)) {
            $links['website'] = $this->website;
        }

        if (!empty($this->portfolio)) {
            $links['portfolio'] = $this->portfolio;
        }

        return $links;
    }

    public function isNotificationsEnabled(): bool
    {
        return $this->email_notifications ?? true;
    }
}