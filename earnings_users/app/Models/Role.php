<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';

    protected $guarded = ['id'];

    // public function backEndUserType(){
    //     return $this->hasMany('App\Models\BackEndUserType','role_id','id');
    // }

    public function backendUser(){
        return $this->belongsToMany('App\Models\BackendUser','backend_user_role','role_id','backend_user_id')->using('App\Models\BackendUserRole')->as('backend_user_role');
    }

    public function permission(){
        return $this->belongsToMany('App\Models\Permission','role_permission','role_id','permission_id');
    }
}
