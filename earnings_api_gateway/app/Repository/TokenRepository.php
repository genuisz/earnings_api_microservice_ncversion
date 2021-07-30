<?php
namespace App\Repository;

use Laravel\Passport\TokenRepository as PassportTokenRepository;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Passport;
use Illuminate\Support\Carbon;
class TokenRepository extends PassportTokenRepository{
/**
 * This Repository is for the binding of singleton of tokenrepository in passport library in the PassportServicecProvider
 * 
 */
    public function find($id)
    {
        return Cache::store('redis')->remember("passport:token:{$id}", config('jwt.ttl')*60 , 
            function () use ($id) {
                return Passport::token()->where('id', $id)->first();
            }
        );
        
    }
    public function create($attributes)
    {
        return Cache::store('redis')->remember("passport:token:{$attributes['id']}", config('jwt.ttl')*60 , 
        function () use ($attributes) {
            return Passport::token()->create($attributes);
        }
    );
    }


        
    

}