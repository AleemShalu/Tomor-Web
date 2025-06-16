<?php

namespace App\Listeners\Api;

use App\Models\User;
use App\Events\Api\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\Api\AccountVerificationNotification;

class SendAccountVerificationNotification
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
    public function handle(UserRegistered $event): void
    {
        // This User object to be passed as $notifiable in the first param of the send method
        $user = User::find($event->user_data->id);
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $user->notify(new AccountVerificationNotification($event->user_data));
        }
    }
}
