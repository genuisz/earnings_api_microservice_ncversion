<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Nicolaslopezj\Searchable\SearchableTrait;
class Product extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;  // Add HasManyDeep relationship
    use HasFactory;
    use SearchableTrait;
    protected $table = 'product';
    protected $fillable = ['category_id','from_port_id','name_en','name_cn','name_zh','factory_id','description','quantity_per_log','quantity_of_log','quantity_reach_target_in_log','quantity_unit_id','price','onsale','tolerance','leadtime','downpayment_ratio','deposit_ratio','duedate','image_url','product_status_type_id','product_no','on_sale'];
    protected $guarded = ['id'];
    //protected $hidden = ['order_product'];

    protected $searchable = [
        'columns'=>[
            'product.name_en'=>10,
            'product.name_zh'=>10,
            'product.name_cn'=>10,
            'product.product_no'=>10,
            'product.description'=>5
        ]
        ];


        /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function orderTransact(){
        return $this->belongsToMany('App\Models\OrderTransact','order_transact_product','product_id','order_transact_id')->withPivot('unique_order_product_id','order_quantity_in_log','order_product_status_id','confirmed_delivery_date','expected_ETD','due_at','downpayment','deposit','sub_total','total','feedback','created_at','updated_at','tracking_no','weight','logistic_cost','col1_rating_id','col2_rating_id','col3_rating_id','col4_rating_id','col_combine_id')->using('App\Models\OrderTransactProduct')->as('order_product');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','id');
    }

    public function orderProduct(){
        return $this->hasMany('App\Models\OrderTransactProduct','product_id','id');
    }

    public function productStatusType(){
        return $this->belongsTo('App\Models\ProductStatusType','product_status_type_id','id');
    }
    public function quantityUnit(){
        return $this->belongsTo('App\Models\QuantityUnit','quantity_unit_id','id');
    }
    public function factorys(){
        return $this->belongsTo('App\Models\Factorys','factory_id','id');
    }
    public function productCommentLike(){
        return $this->hasMany('App\Models\ProductCommentLike','product_id','id');
    }

    public function fromLogisticPort(){
        return $this->belongsTo('App\Models\LogisticPort','from_port_id','id');
    }


    // public function DeepFromPortToPort(){
    //     return $this->hasManyDeep('App\Models\LogisticPort',['App\Models\LogisticPort','logistic_port_logistic'],['id','to_port_id','id'],['from_port_id','id','to_port_id']);
    // }

    // public function users(){
    //     return $this->belongsToMany('App\Models\Users','product_users','product_id','users_id');
    // }

        /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
