<?php

namespace App\Exceptions;

use Exception;

class PermissionNotEnoughException extends Exception
{
    //
    protected $msg;
    public function __construct($msg)
    {
        $this->msg = $msg;
    }
    public function report()
    {
    }

    public function render()
    {
        return response()->json([
            'status'=>'E',
            'message'=>'Unauthorized',
            'debug_message'=> $this->msg
        ], 401);
    }
}