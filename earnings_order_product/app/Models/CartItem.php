<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cart_item';
    protected $fillable = ['cart_id','product_id','quantity_of_log'];
    public function cart(){
        return $this->belongsTo('App\Models\Cart','cart_id','id');
    }
    public function product(){
        return $this->belongsTo('App\Models\Product','product_id','id');
    }

    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
