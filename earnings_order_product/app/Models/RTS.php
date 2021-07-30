<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RTS extends Model
{
    use HasFactory;
    protected $table = 'rts';
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
    // public function users(){
    //     return $this->belongsTo('App\Models\Users','users_id','id');
    // }
    public function quantityUnit(){
        return $this->belongsTo('App\Models\QuantityUnit','quantity_unit_id','id');
    }
}
