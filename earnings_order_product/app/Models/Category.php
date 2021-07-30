<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $fillable =['parent_id','name_en','name_zh','name_cn'];
    protected $guarded = ['id'];
    protected $categoryColumn;
    public function hasChildCategory(){
        return $this->hasMany('App\Models\Category','parent_id','id')->with('hasChildCategory');
    }
    public function hasParentCategory(){
        return $this->belongsTo('App\Models\Category','parent_id','id');
    }
    public function product(){
        return $this->hasMany('App\Models\Product','category_id','id');
    }
    public function rts(){
        return $this->hasMany('App\Models\RTS','category_id','id');
    }
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
