<?php
namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword;

class CustomDatabaseTokenRepository extends DatabaseTokenRepository{


    public function exists(CanResetPassword $user, $token)
    {

        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();


        return $record &&
                $this->hasher->check($token, $record['token']);
    }



}