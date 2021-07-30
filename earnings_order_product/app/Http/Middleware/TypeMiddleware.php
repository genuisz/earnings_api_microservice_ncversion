<?php 
namespace App\Http\Middleware;

use Closure;

class TypeMiddleware
{

    public function handle($request, Closure $next, ...$type )
    {   
  
        $reject = true;
        foreach($type as $r){            
            if($request->user()->isType($r)) {
                $reject = false;               
           }
        }
        if($reject) abort(404);




        return $next($request);

    }
}