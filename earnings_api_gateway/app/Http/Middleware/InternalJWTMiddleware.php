<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use Illuminate\Support\Facades\Redis;
class InternalJWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $param = $request->only(['username','password']);
        $credentials = array('email'=>$param['username'],'password'=>$param['password']);
        if (!$internalToken = auth('jwt')->attempt($credentials)) {
            abort(406);
        }
        
        // try{
        //     // Redis::connection("default")->ping();
        //     // Redis::set('testtt','testt');
        //     Cache::store('redis')->put('testwwws','testww',100);
        // }
        // catch(Exception $e){
        //     dd($e);
        // }
        $request->attributes->add(['internal_token'=>$internalToken]);

        
        //var_dump($internalToken);
        return $next($request);
    }
}

