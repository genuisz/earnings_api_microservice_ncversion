<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    use HasFactory;
    protected $table = 'factory';
    protected $fillable = ['country_id','name_zh','name_cn','name_en','contact_tel','address','email'];
    protected $guarded = ['id'];

    public function country(){
        return $this->belongsTo('App\Models\Country','country_id','id');
    }
    public function backEndUser(){
        return $this->hasOne('App\Models\BackEndUser','id','id');
    }

    // public function product(){
    //     return $this->hasMany('App\Models\Product','factory_id','id');
    // }
}
