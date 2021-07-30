<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'country';

    protected $guarded = ['id'];

    public function factorys(){
        return $this->belongsTo('App\Models\Factorys','country_id','id');
    }
}
