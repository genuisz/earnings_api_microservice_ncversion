<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Event\RegisteredEvent;
use App\Models\User;
use App\Repository\NonLocalRepository\UserRepository;
use Illuminate\Routing\UrlGenerator;
class SendRegisteredConfirmationEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $connection = 'rabbitmq';

    public $queue = 'contact_queue';

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RegisteredEvent $event)
    {
        //
        //dd($event->user);
        $user = new User();


        $user = $user->arrayToUserModel($event->user);

        if (!($user->hasVerifiedEmail())) {
           
            //$event->user->sendEmailVerificationNotification(); 
            $user->sendEmailVerificationNotification();
        }
    }
}
