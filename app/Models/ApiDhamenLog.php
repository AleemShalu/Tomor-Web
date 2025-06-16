<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiDhamenLog extends Model
{
    protected $fillable = [
        'action',
        'request_data',
        'response_data',
        'status',
        'error_message',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
    ];
}