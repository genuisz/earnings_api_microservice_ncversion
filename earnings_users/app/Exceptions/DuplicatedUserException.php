<?php

namespace App\Exceptions;

use Exception;

class DuplicatedUserException extends Exception
{
    //
    protected $msg;
    public function __construct($msg)
    {
        parent::__construct();
        $this->msg = $msg;
    }
    public function render(){
        return response()->json([
            'status'=>'E',
            'message'=>'duplicated user error',
            // 'debug_message'=> $this->getMessage()
            'debug_message'=> $this->msg
        ],404);
    }
}