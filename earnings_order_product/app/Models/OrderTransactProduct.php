<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
class OrderTransactProduct extends Pivot
{
    use HasFactory;
    //protected $primaryKey = ['order_transact_id','product_id'];
    protected $table = 'order_transact_product';
    public function scopeWithHas($query, $relation, $constraint){
        
        
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
    public function product(){
        
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function orderTransact(){
        return $this->belongsTo('App\Models\OrderTransact','order_transact_id','id');
    }
    public function orderStatus(){
        return $this->belongsTo('App\Models\OrderStatus','order_product_status_id','id');
    }
    // public function payment(){
    //     return $this->hasMany('App\Models\Payment','unique_order_product_id','order_product_id');
    // }
}
