<?php
namespace App\Listeners;
class OrderEventListener {

    public function subscribe($events){
        $events->listen(
            'App\Events\OrderCreated',
            'App\Listeners\OrderEventListener@onOrderCreated'
        );
    }


}