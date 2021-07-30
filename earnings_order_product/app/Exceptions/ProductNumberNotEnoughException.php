<?php

namespace App\Exceptions;

use Exception;

class ProductNumberNotEnoughException extends Exception
{
    //
    public function report(){

    }

    public function render(){
        return response()->json([
            'status'=>'E',
            'message'=>'Product quantity exceed inventory',
            'debug_message'=> $this->getMessage()
        ],404);
    }
}