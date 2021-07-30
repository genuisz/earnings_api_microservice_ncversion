<?php

namespace App\Exceptions;

use Exception;

class InputDataErrorException extends Exception
{
    //
    protected $msg;
    public function __construct($msg)
    {
        $this->msg = $msg;
    }
    public function report(){

    }

    public function render(){
        return response()->json([
            'status'=>'E',
            'message'=>'Input Data Error',
            'debug_message'=> $this->msg
        ],500);
    }
}
