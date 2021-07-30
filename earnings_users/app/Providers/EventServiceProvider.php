<?php

namespace App\Providers;

use App\Events\RegisteredEvent;
use App\Listeners\SendRegisteredConfirmationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Event\RegisteredEvent'=>[
            'App\Listeners\SendConfirmationEmail',
        ],
        'App\Event\ChangedPasswordEvent'=>[
            'App\Listeners\ChangedPasswordEmailNotification',
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
}
