<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
class EmailVerificaitonCustomRequest extends EmailVerificationRequest

{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $user;
    public function authorize()
    {
        $user = User::find($this->route('id'));


        if (!hash_equals((string) $this->route('hash'), sha1($user->getEmailForVerification()))) {
           return false;
            
        }
    
        $this->user = $user;
        event(new Verified($user));
        return true;


    }
    public function fulfill(){
        if (!$this->user->hasVerifiedEmail()){
            $this->user->markEmailAsVerified();
        }
        else{
            
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

}
