<?php
namespace App\Exceptions;

use Exception;

class HttpReponseInputErrorException extends Exception{
    protected $msg;
    protected $errorBag;
    public function __construct($msg,$errorBag)
    {
        $this->msg = $msg;
        $this->errorBag = $errorBag;
    }


    public function render(){
        return response()->json([
            'status'=>'E',
            'message'=>'Input Data Error',
            'error'=>$this->errorBag,
            'debug_message'=> $this->msg
        ],422);
    }
}