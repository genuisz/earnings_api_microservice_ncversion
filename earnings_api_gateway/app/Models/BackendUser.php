<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Passport\HasApiTokens;
// use Laravel\Sanctum\HasApiTokens;
class BackendUser extends Authenticatable implements JWTSubject
{
    use HasPermissionsTrait;
    use HasFactory;
    use HasApiTokens;
    
    protected $table = 'backend_user';
    protected $fillable = ['username','password'];
    protected $guarded = ['id','status'];

    // public function factorys(){
    //     return $this->belongsTo('App\Models\Factory','id','id');
    // }
    // public function backEndUserType(){
    //     return $this->belongsTo('App\Models\BackEndUserType','type_id','id');
    // }
    public function role(){
        return $this->belongsToMany('App\Models\Role','backend_user_role','backend_user_id','role_id')->using('App\Models\BackendUserRole')->as('backend_user_role');
    }
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
    public function permission() {

        // return $this->belongsToMany(Permission::class,'users_permissions');
        return $this->hasManyDeep('App\Models\Permission',['backend_user_role','role_permission'],['backend_user_id','role_id','id'],['id','role_id','permission_id']);
      }

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
}
