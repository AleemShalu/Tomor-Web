<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AccountDeletionRequest extends Model
{
    protected $fillable = ['email', 'token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];


    // Generate a token and expiration time
    public static function createRequest($email)
    {
        return self::create([
            'email' => $email,
            'token' => Str::random(64),
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);
    }

    // Check if the token is still valid
    public function isValid()
    {
        return $this->expires_at->isFuture();
    }
}