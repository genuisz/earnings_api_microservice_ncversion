<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AddressBook extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'address_book';
    protected $fillable =['first_name','last_name','email','country','address_line','city','zip','state','phone'];
    protected $guarded = ['id','users_id'];

    public function user(){
        return $this->belongsTo('App\Models\User','users_id','id');
    }
}
