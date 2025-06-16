<?php

namespace App\Notifications\Api;

use App\Mail\Api\ForgetPassowrdVerificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ForgetPasswordVerificationNotification extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new ForgetPassowrdVerificationMail($this->user_data))
        ->to($notifiable->email);
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
