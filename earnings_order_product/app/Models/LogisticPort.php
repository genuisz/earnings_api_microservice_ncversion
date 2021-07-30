<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticPort extends Model
{
    use HasFactory;
    protected $table = 'logistic_port';
    
    public function country(){
        return $this->belongsTo('App\Models\Country','country_id','id');

    }
    public function portType(){
        return $this->belongsTo('App\Models\PortType','port_type_id','id');
    }
    public function logisticFrom(){
        return $this->belongsToMany('App\Models\Logistic','logistic_port_logistic','from_port_id','logistic_id')->withPivot(['to_port_id','courier_charges_unit'])->using('App\Models\LogisticPortLogistic');
    }
    public function logisticTo()
    {
        return $this->belongsToMany('App\Models\Logistic','logistic_port_logistic','to_port_id','logistic_id')->withPivot(['from_port_id','courier_charges_unit'])->using('App\Models\LogisticPortLogistic');
    }

    public function logisticPortFrom(){
        return $this->belongsToMany('App\Models\LogisticPort','logistic_port_logistic','to_port_id','from_port_id')->withPivot(['logistic_id','courier_charges_unit'])->using('App\Models\LogisticPortLogistic');
    }
    public function logisticPortTo(){
        return $this->belongsToMany('App\Models\LogisticPort','logistic_port_logistic','from_port_id','to_port_id')->withPivot(['logistic_id','courier_charges_unit'])->using('App\Models\LogisticPortLogistic');
    }


    public function toPivotLogisticPort(){
        return $this->hasMany('App\Models\LogisticPortLogistic','to_port_id','id');
    }

    public function product(){
        return $this->hasMany('App\Models\Product','from_port_id','id');
    }

    public function orderTransact(){
        return $this->hasMany('App\Models\OrderTransact','from_port_id','id');
    }

    public function toLogisticFromDeep(){
        return $this->hasManyDeep('App\Models\LogisticPort',['backend_user_role','role_permission'],['backend_user_id','role_id','id'],['id','role_id','permission_id']);
    }
}
