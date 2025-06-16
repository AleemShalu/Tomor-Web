<?php

namespace App\Listeners\Api;

use App\Events\Api\PhoneOtpRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Api\PhoneOtpNotification;
use App\Notifications\Channels\WhatsAppChannel;

class SendPhoneOtpNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PhoneOtpRequested $event): void
    {
        // This User object to be passed as $notifiable in the first param of the send method
        // $user = User::find($event->user_data->id);
        // $user->notify(new PhoneOtpNotification($event->user_data));
        Notification::route(WhatsAppChannel::class, new WhatsAppChannel())
            ->notify(new PhoneOtpNotification($event->user_data));

    }
}
