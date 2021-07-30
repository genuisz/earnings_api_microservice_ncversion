<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Event\EmailVerifiedEvent;
class SendCongratulationsEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
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
    public function handle(EmailVerifiedEvent $event)
    {
        //
        dump($event->data);
        Mail::to($event->data['email'])->send(new SendRegisterConfimationMailable($event->data));
    }
}
