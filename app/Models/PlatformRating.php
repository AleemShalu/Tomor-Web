<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformRating extends Base
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'platform',
        'body_massage',
        'rating',
    ];

    /**
     * Get the user that owns the rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}