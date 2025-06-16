<?php

namespace App\Notifications\Channels;
use App\Notifications\SendMessage;
use Illuminate\Notifications\Notification;

class WebChannel
{
    public function send($notifiable, Notification $notification)
    {
        if ($notification instanceof SendMessage) {
            $message = $notification->getMessage();
        }
    }
}