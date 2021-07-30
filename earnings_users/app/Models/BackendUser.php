<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class BackendUser extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'backend_user';
    protected $fillable = ['username'];
    protected $guarded = ['id','password','type_id','status'];

    public function factorys(){
        return $this->belongsTo('App\Models\Factorys','id','id');
    }
    // public function backEndUserType(){
    //     return $this->belongsTo('App\Models\BackEndUserType','type_id','id');
    // }

    public function role(){
        return $this->belongsToMany('App\Models\Role','backend_user_role','backend_user_id','role_id')->using('App\Models\BackendUserRole')->as('backend_user_role');
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
        return [];
    }
}
