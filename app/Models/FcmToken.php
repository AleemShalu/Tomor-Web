<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow Laravel's naming convention
    protected $table = 'fcm_tokens';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'fcm_token',
        'device_type',
        'device_model',
        'locale',
    ];

    /**
     * Get the user that owns the FcmToken.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}