<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BackendUserRole extends Pivot{
    protected $table= 'backend_user_role';

    public function backendUser(){
        return $this->belongsTo('App\Models\BackendUser','backend_user_id','id');
    }
    public function role(){
        return $this->belongsTo('App\Models\Role','role_id','id');
    }

}