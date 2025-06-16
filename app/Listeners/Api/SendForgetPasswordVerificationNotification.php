<?php

namespace App\Listeners\Api;

use App\Events\Api\ForgetPasswordRequested;
use App\Models\User;
use App\Notifications\Api\ForgetPasswordVerificationNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendForgetPasswordVerificationNotification
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
    public function handle(ForgetPasswordRequested $event): void
    {
        // This User object to be passed as $notifiable in the first param of the send method
        $user = User::find($event->user_data->id);
        $user->notify(new ForgetPasswordVerificationNotification($event->user_data));
    }
}
