<?php
namespace App\Mailable;

use Illuminate\Mail\Mailable;
use App\Models\OrderTransact;
class SendRegisterConfimationMailable extends Mailable{

    public $data;
    public function __construct($data)
    {
        $this->data =$data;
        
    }



    public function build(){

        $data = array('name'=>json_encode($this->data),'body'=>'A test mail');
            return $this->view('mail',$data);
    }
}