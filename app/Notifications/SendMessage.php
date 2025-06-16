<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

// Import the Notification class

class SendMessage extends Notification
{
    protected $recipient;
    protected $type;
    protected $channels;
    protected $message;
    protected $subject;

    public function __construct($recipient, $type, $channels, $message, $subject)
    {
        $this->recipient = $recipient;
        $this->type = $type;
        $this->channels = $channels;
        $this->message = $message;
        $this->subject = $subject;
    }

    // Add a public getter method for the message
    public function getMessage()
    {
        return $this->message;
    }

    public function toDatabase($notifiable)
    {
        $notificationData = [
            'id' => (string)Str::uuid(),
            'type' => $this->type,
            'recipient_id' => $this->recipient->id,
            'title' => $this->subject,
            'message' => $this->message,
        ];

        // Filter the active channels and join them into a comma-separated string
        $activeChannels = array_intersect($this->channels, ['mail', 'database', 'whatsapp', 'app', 'web']);

        if (!empty($activeChannels)) {
            $notificationData['channel'] = implode(', ', $activeChannels);
        }

        return $notificationData;
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject) // Use the custom subject
            ->line('You have received a new notification.')
            ->line($this->message) // Include the custom message
            ->action('View Notification', url('/')); // Replace with the URL you want to link to in the email
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'whatsapp', 'app', 'web'];
    }
}
