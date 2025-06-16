<?php

namespace App\Providers;

use App\Notifications\Channels\AppChannel;
use App\Notifications\Channels\FirebaseChannel;
use App\Notifications\Channels\WebChannel;
use App\Notifications\Channels\WhatsAppSendChannel;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Notification::extend('whatsapp', function ($app) {
            return new WhatsAppSendChannel();
        });

        Notification::extend('app', function ($app) {
            return new AppChannel();
        });

        Notification::extend('web', function ($app) {
            return new WebChannel();
        });

        Notification::extend('firebase', function ($app) {
            return new FirebaseChannel();
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar', 'en']);
        });

        if(env('APP_ENV') !== 'local')
        {
            // URL::forceScheme('https');
        }

        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
        ]);

    }
}
