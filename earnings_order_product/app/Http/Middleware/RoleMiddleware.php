<?php 
namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{

    public function handle($request, Closure $next, ...$role )
    {   
        $reject = true;
        foreach($role as $r){            
            if($request->user()->hasRole($r)) {
                $reject = false;               
           }
        }
        if($reject) abort(404);




        return $next($request);

    }
}