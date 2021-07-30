<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardPoint extends Model
{
    use HasFactory;
    protected $table = 'reward_point';
    
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function users(){
        return $this->belongsTo('App\Models\Users','users_id','id');
    }
    public function rewardType(){
        return $this->belongsTo('App\Models\RewardType','reward_type_id','id');
    }
}
