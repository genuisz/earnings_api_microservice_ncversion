<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStatusType extends Model
{
    use HasFactory;
    protected $table = 'product_status_type';
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
    public function product(){
        return $this->hasMany('App\Models\Product','product_status_type_id','id');
    }

}
