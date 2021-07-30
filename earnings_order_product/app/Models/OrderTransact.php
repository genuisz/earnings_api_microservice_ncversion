<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
class OrderTransact extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'order_transact';
    public $incrementing = false;

    protected $fillable =['sub_total','logistic_cost','downpayment','deposit','total_price','logistic_id','logistic_tracking_no','to_port_id'];
    protected $guarded = ['id','users_id','order_status_id'];
    //protected $hidden = ['order_product'];
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
            /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // public function users(){
    //     return $this->belongsTo('App\Models\Users','users_id','id');
    // }
    public function logistic(){
        return $this->belongsTo('App\Models\Logistic','logistic_id','id');
    }
    public function orderStatus(){
        return $this->belongsTo('App\Models\OrderStatus','order_status_id','id');
    }

    // public function payment(){
    //     return $this->hasMany('App\Models\Payment','order_transact_id','id');
    // }

    public function product(){
        return $this->belongsToMany('App\Models\Product','order_transact_product','order_transact_id','product_id')->withPivot('unique_order_product_id','order_quantity_in_log','order_product_status_id','confirmed_delivery_date','expected_ETD','due_at','downpayment','deposit','sub_total','total','feedback','created_at','updated_at','tracking_no','weight','logistic_cost','col1_rating_id','col2_rating_id','col3_rating_id','col4_rating_id','col_combine_id')->using('App\Models\OrderTransactProduct')->as('order_product');
    }
    // public function product(){
    //     return $this->belongsToMany('App\Models\Product','order_transact_product','order_transact_id','product_id')->withPivot(['order_quantity_in_log'])->using('App\Models\OrderTransactProduct')->as('order_product');
    // }
    public function productPart(){
        return $this->belongsToMany('App\Models\Product','order_transact_product','order_transact_id','product_id')->using('App\Models\OrderTransactProduct')->as('order_product');
    }

    public function orderProduct(){
        return $this->hasMany('App\Models\OrderTransactProduct','order_transact_id','id');
    }

    public function toLogisticPort(){
        return $this->belongsTo('App\Models\LogisticPort','to_port_id','id');
    }
}
