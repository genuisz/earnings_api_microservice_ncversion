<?php
namespace App\Listeners;

use App\Event\FailureGroupPurchase;
use App\Event\OrderCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
class SendFailureGroupPurchaseEmail extends ShouldQueue{

    public $connection = 'rabbitmq';
    public $queue = 'contact_queue';
    public function __construct()
    {
        //
    }



    /**
     * Handle the event.
     *
     * @param  OrderCreatedEvent  $event
     * @return void
     */
    public function handle(FailureGroupPurchase $event)
    {
        
    }
}