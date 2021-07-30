<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardPointType extends Model
{
    use HasFactory;
    protected $table = 'reward_point_type';
    public function rewardPoint(){
        return $this->hasMany('App\Models\RewardPoint','reward_type_id','id');
    }
}
