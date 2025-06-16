<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        $messaging = app(Messaging::class);

        $fcmToken = $this->getFcmToken($notifiable, $notification) ?? null;
        $notificationData = $this->getNotificationData($notification, $notifiable);

        if (!$fcmToken) {
            Log::info('FCM token not available for user ' . $notifiable->id);
            return;
        } else {
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notificationData);
            $messaging->send($message);
        }
    }

    protected function getFcmToken($notifiable, Notification $notification)
    {
        return ($notifiable->routeNotificationFor('firebase') ?? $notification->fcm_token) ?? null;
    }

    protected function getNotificationData(Notification $notification, $notifiable)
    {
        $data = [
            'title' => $notification->toFirebase($notifiable)['title'] ?? '',
            'body' => $notification->toFirebase($notifiable)['body'] ?? '',
        ];

        return array_filter($data); // Remove null or empty values
    }
}
