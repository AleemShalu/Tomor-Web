<?php

namespace App\Providers;

use App\Events\Api\ForgetPasswordRequested;
use App\Events\Api\PhoneOtpRequested;
use App\Events\Api\UserRegistered;
use App\Listeners\Api\SendAccountVerificationNotification;
use App\Listeners\Api\SendForgetPasswordVerificationNotification;
use App\Listeners\Api\SendPhoneOtpNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserRegistered::class => [
            SendAccountVerificationNotification::class,
        ],
        PhoneOtpRequested::class => [
            SendPhoneOtpNotification::class,
        ],
        ForgetPasswordRequested::class => [
            SendForgetPasswordVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
