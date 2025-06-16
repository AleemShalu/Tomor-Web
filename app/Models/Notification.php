<?php

namespace App\Models;

class Notification extends Base
{
    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'notifiable_id',
        'notifiable_type',
        'sender_id',
        'recipient_id',
        'type',
        'data',
        'channel',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    
}