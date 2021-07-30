<?php

namespace App\Listeners;

use App\Event\OrderCreatedEvent;
use App\Mailable\SendOrderToCustomerMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class SendOrderConfrimationEmail implements ShouldQueue
{

    public $connection = 'rabbitmq';

    public $queue = 'contact_queue';


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
     * @param  OrderCreatedEvent  $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {

        // $data = array('name'=>$event->order['downpayment'],'body'=>'A test mail');

        // Mail::send('mail',$data,function($message){
        //     $message->to('itsup.earnings@gmail.com','test')->subject('test');
        //     $message->from('itsup.earnings@gmail.com','test');
        // });

        Mail::to('itsup.earnings@gmail.com')->send(new SendOrderToCustomerMailable($event->data));


    }


}
