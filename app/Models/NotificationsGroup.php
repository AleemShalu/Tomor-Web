<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationsGroup extends Base
{
    use HasFactory;

    protected $table = 'notifications_group'; // Specify the table name if different from the model name
    protected $fillable = [
        'notification_type',
        'platform_type',
        'users_type',
        'notification_title_ar',
        'notification_message_ar',
        'notification_title_en',
        'notification_message_en',
    ];
}
