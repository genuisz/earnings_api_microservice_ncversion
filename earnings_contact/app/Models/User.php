<?php

namespace App\Models;

use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\NonLocalRepository\UserRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
class User extends Authenticatable implements  MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function SendEmailVerificationNotification(){
        // VerifyEmail::createUrlUsing(function($notifiable){
        //     return URL::temporarySignedRoute(
        //         'verification.verify',
        //         Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
        //         [
        //             'id' => $notifiable->getKey(),
        //             'hash' => sha1($notifiable->getEmailForVerification()),
        //         ]
        //         ,false
        //     );

        // });
        // VerifyEmail::toMailUsing(function ($notifiable,$url){
            
        //     $mail = new MailMessage;
        //     $from_address = config('mail.from.address');
        //     $from_name = 'test';
        //     $mail->from($from_address, $from_name);
        //     $mail->subject('test');
        //     $mail->line('test');
        //     $mail->line($url);
        //     $mail->line('test');
        //     $mail->line('test');
        //     $mail->salutation('test');
        //     return $mail;
        // });

        parent::sendEmailVerificationNotification();
        
    }
    // public function selfSignedRouted($name,$parameters=[], $expiration= null,$absolute=true){
    //     $parameters = Arr::wrap($parameters);

    //     if (array_key_exists('signature', $parameters)) {
    //         throw new InvalidArgumentException(
    //             '"Signature" is a reserved parameter when generating signed routes. Please rename your route parameter.'
    //         );
    //     }

    // }

    // public function hasVerifiedEmail()
    // {
    //     return !is_null($this->email_verifiied_at);
    // }

    // public function markEmailAsVerified(){
    //     $repo = new UserRepository();
    //     $repo->verifyUser($this['id']);

    // }

    public function arrayToUserModel($array){
        $user = new User();
        foreach($array as $key=>$value){
            $user->$key= $value;
        }
        return $user;
    }
}
