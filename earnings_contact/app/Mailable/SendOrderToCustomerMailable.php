<?php
namespace App\Mailable;

use Illuminate\Mail\Mailable;
use App\Models\OrderTransact;
class SendOrderToCustomerMailable extends Mailable{

    public $orderTransact;
    public $logisticColumn;
    public $logisticPortColumn;
    public $orderStatusColumn;
    public $orderProductColumn;
    public function __construct($orderTransact)
    {
        $this->orderTransact =$orderTransact;
        
    }



    public function build(){

        $data = array('name'=>json_encode($this->orderTransact),'body'=>'A test mail');
            return $this->view('mail',$data);
    }
}