<?php


// app/Notifications/FirebaseNotification.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class FirebaseNotification extends Notification
{
    protected $fcmToken;
    protected $title;
    protected $body;

    public function __construct($fcmToken, $title, $body)
    {
        $this->fcm_token = $fcmToken;
        $this->title = $title;
        $this->body = $body;
    }

    public function toFirebase($notifiable)
    {
        return [
            'fcm_token' => $this->fcm_token ?? null,
            'title' => $this->title,
            'body' => $this->body,
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'fcm_token' => $this->fcm_token,
        ];
    }

    public function via($notifiable)
    {
        return ['database', 'firebase']; // Include the channels you want to use
    }
}
