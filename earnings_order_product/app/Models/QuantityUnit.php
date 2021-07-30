<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantityUnit extends Model
{
    use HasFactory;
    protected $table = 'quantity_unit';
   
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
    public function product(){
        return $this->hasMany('App\Models\Product','quantity_unit_id','id');
    }

    public function rts(){
        return $this->hasMany('App\Models\RTS','quantity_unit_id','id');
    }
}
