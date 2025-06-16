<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationType extends Base
{
    use HasFactory;

    protected $fillable = ['code_type'];

    // Define the relationship with localized notifications
    public function localizedNotifications()
    {
        return $this->hasMany(LocalizedNotification::class, 'notification_type_id');
    }

}
