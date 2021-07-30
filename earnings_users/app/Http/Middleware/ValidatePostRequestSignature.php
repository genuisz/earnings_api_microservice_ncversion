<?php

namespace App\Http\Middleware;

use App\Exceptions\SignatureInvalidException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
class ValidatePostRequestSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $status =$this->hasValidSignature($request);
        if(!$status) throw  new SignatureInvalidException('');

        return $next($request);
    }

    public function hasValidSignature(Request $request){
        return $this->hasCorrectSignature($request)
        && $this->signatureHasNotExpired($request);
    }
    public function hasCorrectSignature(Request $request){
        $array  = Arr::except($request->all(),['signature','password','password_confirmation']);
        $checkUrl=route('password.reset', $array, false).'';
        $signature = hash_hmac('sha256', $checkUrl,app()->make('config')->get('app.key'));

        return hash_equals($signature, (string) $request['signature']) ;
    }

    public function signatureHasNotExpired(Request $request){
        $expires = $request['expires'];
        dump($expires);
        dump(Carbon::now()->getTimestamp());
        return ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }
}
