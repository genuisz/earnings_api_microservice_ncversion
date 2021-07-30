<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackendUserType extends Model
{
    use HasFactory;
    protected $table = 'backend_user_type';

    protected $guarded = ['id'];

    public function role(){
        return $this->belongsTo('App\Models\Role','role_id','id');
    }
    public function backEndUser(){
        return $this->hasMany('App\Models\BackendUser','type_id','id');
    }
}
