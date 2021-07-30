<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserInfo extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'users_info';
    protected $fillable = ['name','delivery_address1','delivery_address2','contact_no','notification_status','country_id','business_nature_id','interested_category','company_website','company_address','gender'];
    protected $guarded = ['id','reward_point','recent_ip','registered_ip','status'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function users(){
        return $this->belongsTo('App\Models\User','id','id');
    }

    public function addressBook(){
        return $this->hasMany('App\Models\AddressBook','users_id','id');
    }
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }

    public function country(){
        return $this->belongsTo('App\Models\Country','country_id','id');
    }
    public function business(){
        return $this->belongsTo('App\Models\BusinessNature','business_nature_id','id');
    }
}
