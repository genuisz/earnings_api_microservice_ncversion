<?php
namespace App\ServiceProvider;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Arr;

class CustomPasswordBroker extends PasswordBroker{

    protected function validateReset(array $credentials)
    {   


        if (is_null($user = $this->getUser(Arr::only($credentials,['email','token'])))) {
           
            return static::INVALID_USER;
        }
       
        if (! $this->tokens->exists($user, $credentials['token'])) {
            return static::INVALID_TOKEN;
        }



        return $user;
    }
}