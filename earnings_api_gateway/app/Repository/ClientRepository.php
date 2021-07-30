<?php
namespace App\Repository;

use Laravel\Passport\ClientRepository as PassportClientRepository;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Passport;
class ClientRepository extends PassportClientRepository{
/**
 * This Repository is for the binding of singleton of ClientRepository in passport library in the PassportServicecProvider
 * in order to call all of the clientRepository with this class
 * 
 */
    public function find($id)
    {
        
        return Cache::store('redis')->remember("passport:client:{$id}",config('jwt.ttl')*60 , 
        function () use ($id) {
            $client = Passport::client();
            return $client->where($client->getKeyName(), $id)->first();
        }
    );
    }

}