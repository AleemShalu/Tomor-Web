<?php

namespace App\Jobs;

use App\Notifications\SendMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sender;
    protected $user;
    protected $notificationType;
    protected $notificationChannels;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct($sender, $user, $notificationType, $notificationChannels, $message)
    {
        $this->sender = $sender;
        $this->user = $user;
        $this->notificationType = $notificationType;
        $this->notificationChannels = $notificationChannels;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Retrieve parameters from the job payload
        $sender = $this->sender;
        $user = $this->user;
        $notificationType = $this->notificationType;
        $notificationChannels = $this->notificationChannels;
        $message = $this->message;

        // Create and send the notification
        $user->notify(new SendMessage($sender, $user, $notificationType, $notificationChannels, $message));
    }
}