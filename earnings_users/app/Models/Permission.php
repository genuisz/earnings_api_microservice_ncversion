<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permission';

    protected $guarded = ['id'];

    public function role(){
        return $this->belongsToMany('App\Models\Role','role_permission','permission_id','role_id');
    }
}
