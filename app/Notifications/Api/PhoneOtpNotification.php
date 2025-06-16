<?php

namespace App\Notifications\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Channels\WhatsAppChannel;

class PhoneOtpNotification extends Notification
{
    use Queueable;

    public $user_data;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_data)
    {
        $this->user_data = $user_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class, 'database'];
    }

    /**
     * Get the WhatsApp message representation of the notification.
     */
    public function toWhatsApp(object $notifiable)
    {
        return (new WhatsAppChannel())->send($notifiable, $this);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_data' => $this->user_data,
        ];
    }
}
