<?php

namespace App\Models;

use App\Notification\GenerateVerificationURL;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;
class User extends Authenticatable implements  AuthenticatableContract,MustVerifyEmail,CanResetPassword
{
    use HasFactory, Notifiable,SoftDeletes,ResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


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

    protected $table = 'users';
    protected $fillable = ['email','username','password'];
    protected $guarded = ['id'];

    public function generateVerificationURL(){
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ],
            false
        );
        
    }
    public function generateResetPasswordURL($token){
        return route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false).'';
    }

    public function generateResetPasswordURL2($token){

        return URL::temporarySignedRoute(
            'password.reset',
            Carbon::now()->addMinutes(config('auth.passwords.users.expire',60)),
            [
                'token'=>$token,
                'id'=>$this->getKey(),
                'email'=>sha1($this->getEmailForPasswordReset())
            ],
            false
            );
    }
    // public function SendEmailVerificationNotification(){
    //     VerifyEmail::toMailUsing(function ($notifiable,$url){

    //     });

    //     parent::sendEmailVerificationNotification();
        
    // }

    

    // Rest omitted for brevity

    // /**
    //  * Get the identifier that will be stored in the subject claim of the JWT.
    //  *
    //  * @return mixed
    //  */
    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    // /**
    //  * Return a key value array, containing any custom claims to be added to the JWT.
    //  *
    //  * @return array
    //  */
    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function userinfo(){
        return $this->hasOne('App\Models\UserInfo','id','id');
    }

    public function passwordReset(){
        return $this->hasMany('App\Models\PasswordReset','email','email');

    }
    // public function productCommentLike(){
    //     return $this->hasMany('App\Models\ProductCommentLike','users_id','id');
    // }
    public function rewardPoint(){
        return $this->hasMany('App\Models\RewardPoint','users_id','id');

    }

    public function addressBook(){
        return $this->hasMany('App\Models\AddressBook','users_id','id');
    }
    // public function conversation(){
    //     return $this->hasMany('App\Models\Conversation','users_id','id');
    // }
    // public function rts(){
    //     return $this->hasMany('App\Models\RTS','users_id','id');
    // }
    // public function message(){
    //     return $this->hasMany('App\Models\Message','users_id','id');
    // }
    // public function product(){
    //     return $this->belongsToMany('App\Models\Product','product_users','users_id','product_id');
    // }



    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }
public function getAuthIdentifier()
    {
        return $this->id;
    }
public function getAuthPassword()
    {
        return null;
    }
public function getRememberToken()
    {
        return null;
    }
    public function setRememberToken($value) {}
    public function getRememberTokenName() {}

    public function isAllowPermission($value){
        if(in_array($value,$this->permission)){
            return true;
        }
        return false;
    }

}
