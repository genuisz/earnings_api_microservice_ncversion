<?php

namespace App\Listeners;

use App\Event\ChangedPasswordEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangedPasswordEmailNotification implements ShouldQueue
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
    public function handle(ChangedPasswordEvent $event)
    {
        //
        dump($event->data);
    }
}
