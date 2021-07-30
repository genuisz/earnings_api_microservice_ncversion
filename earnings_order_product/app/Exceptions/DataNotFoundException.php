<?php

namespace App\Exceptions;

use Exception;

class DataNotFoundException extends Exception
{
    //
    public function report(){

    }

    public function render(){
        return response()->json([
            'status'=>'E',
            'message'=>'Data Not Found',
            'debug_message'=> $this->getMessage()
        ],404);
    }
}
