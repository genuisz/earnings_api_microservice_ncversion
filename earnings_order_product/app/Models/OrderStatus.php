<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    protected $table = 'order_status';
    protected $fillable =['name_en','name_zh','name_cn'];
    protected $guarded = ['id']; 
    public function orderTransact(){
        return $this->hasMany('App\Models\OrderTransact','order_status_id','id');
    }
    public function orderTransactProduct(){
        return $this->hasMany('App\Models\OrderTransactProduct','order_product_status_id','id');
    }

    public function parentOrderStatus(){
        return $this->belongsTo('App\Models\OrderStatus','parent_id','id');
    }

    public function childOrderStatus(){
        return $this->hasMany('App\Models\OrderStatus','parent_id','id');
    }
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
