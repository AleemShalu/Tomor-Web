<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocalizedNotification extends Base
{
    use HasFactory;

    protected $fillable = ['notification_type_id', 'locale', 'name', 'description'];

    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class, 'notification_type_id');
    }

}