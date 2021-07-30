<?php 
namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{

    public function handle($request, Closure $next, ...$permission )
    {

        foreach($permission as $p){
            $reject = true;
            if($request->user()->can($p)) {
                $reject = false;               
           }
        }
        if($reject) abort(404);



        



        return $next($request);

    }
}