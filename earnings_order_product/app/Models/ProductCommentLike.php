<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCommentLike extends Model
{
    use HasFactory;
    protected $table = 'product_comment_like';
    protected $fillable =['comment','type','like','product_id'];
    protected $guarded =['id','users_id'];
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
    public function product(){
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    // public function users(){
    //     return $this->belongsTo('App\Models\Users','users_id','id');
    // }
}
