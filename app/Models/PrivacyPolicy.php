<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrivacyPolicy extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'privacy_policy_approved_by_users', 'privacy_policy_id', 'user_id')
            ->withPivot('approved')->withTimestamps();
    }
}
