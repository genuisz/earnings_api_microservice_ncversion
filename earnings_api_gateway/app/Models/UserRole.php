<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot{
    protected $table= 'user_role';

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function role(){
        return $this->belongsTo('App\Models\Role','role_id','id');
    }

}