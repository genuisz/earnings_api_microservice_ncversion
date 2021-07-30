<?php 
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Hash;

class CheckPasswordMiddleware{

    public function handle($request, Closure $next)
    {
        $user = $request->user();
        dd($user);
        $allow = Hash::check($request->password,$user->password);

        if($allow){
            return $next($request);
        }
        
        abort(401);
        
    }
}