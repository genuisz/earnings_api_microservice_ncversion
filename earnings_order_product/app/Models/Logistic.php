<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{

    use HasFactory;
    protected $table = 'logistic';
    protected $fillable =['contact_tel','name_en','name_zh','name_cn','email'];
    protected $guarded = ['id'];

    public function orderTransact(){
        return $this->hasMany('App\Models\OrderTransact','order_transact_id','id');
    }
    public function logisticPortFrom(){
        return $this->belongsToMany('App\Models\LogisticPort','logistic_port_logistic','logistic_id','from_port_id')->withPivot(['to_port_id','courier_charges_unit'])->using('App\Models\LogisticPortLogistic');

    }
    public function logisticPortTo(){
        return $this->belongsToMany('App\Models\LogisticPort','logistic_port_logistic','logistic_id','to_port_id')->withPivot(['from_port_id','courier_charges_unit'])->using('App\Models\LogisticPortLogistic');
    }

    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
