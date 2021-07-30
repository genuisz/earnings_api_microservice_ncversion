<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
class CacheUserProvider extends EloquentUserProvider
{

    /**
     * This Provider extends the eloquent user provide with cache the data in redis
     * in order to relieve the frequency of processing database
     */

    public function retrieveById($identifier)
    {
        
        return Cache::store('redis')->remember($this->model.':'.'cachedUser:'.$identifier, 60*config('jwt.ttl'), function() use($identifier) {
            return parent::retrieveById($identifier);
        });
                    
                    
    }
    
}
