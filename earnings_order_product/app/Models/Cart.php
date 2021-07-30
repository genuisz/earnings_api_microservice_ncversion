<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cart';
    protected $guarded = ['id','users_id'];

    public function cartItem(){
        return $this->hasMany('App\Models\CartItem','cart_id','id');
    }
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
