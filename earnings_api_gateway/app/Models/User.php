<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasPermissionsTrait;
    protected $table = 'users';
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

        /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        
        return [
            'role'=>$this->role->map(function($data){
                return $data->slug;
            })->toArray(),
            'permission'=>$this->permission->map(function($data){
                return $data->slug;
            })->toArray(),
            'type'=>class_basename($this)
        ];
    }

    public function role(){
        return $this->belongsToMany('App\Models\Role','user_role','user_id','role_id')->using('App\Models\UserRole')->as('user_role');
    }
    public function permission() {

        // return $this->belongsToMany(Permission::class,'users_permissions');
        return $this->hasManyDeep('App\Models\Permission',['user_role','role_permission'],['user_id','role_id','id'],['id','role_id','permission_id']);
      }

}
