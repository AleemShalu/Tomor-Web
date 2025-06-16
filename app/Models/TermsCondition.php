<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TermsCondition extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'terms_conditions_approves_by_users', 'terms_condition_id', 'user_id')
            ->withPivot('approved')->withTimestamps();
    }
}
