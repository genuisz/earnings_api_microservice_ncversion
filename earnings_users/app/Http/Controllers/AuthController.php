<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificaitonCustomRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Service\UserService;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
class AuthController extends Controller
{
    //
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }

    public function authEmail(EmailVerificaitonCustomRequest $request){


        $result = $request->fulfill();

    }

    public function forgotPassword(Request $request){
        return $this->userService->forgotPassword($request);
    }

    public function resetPassword(Request $request){
        // $url =  '/'.$request->path() ;
        // dump($url);
        // dump($request->server->get('QUERY_STRING'));
        // $queryString = ltrim(preg_replace('/(^|&)signature=[^&]+/', '', $request->server->get('QUERY_STRING')), '&');
        // dump($queryString);
        // dump(rtrim($url.'?'.$queryString, '?'));
        // $signature = hash_hmac('sha256', rtrim($url.'?'.$queryString, '?'), env('APP_KEY'));

        // dump(hash_equals($signature, (string) $request->query('signature', ''))); 

        return $this->userService->resetPassword($request->only('email','password','password_confirmation','token','id','expires','signature'));
    }

    public function firstStepResetPassword(Request $request){
        // $url =  '/'.$request->path();

        // $queryString = ltrim(preg_replace('/(^|&)signature=[^&]+/', '', $request->server->get('QUERY_STRING')), '&');

        // dump($queryString);
        // dump(rtrim($url.'?'.$queryString, '?'));
        // $signature = hash_hmac('sha256', rtrim($url.'?'.$queryString, '?'), app()->make('config')->get('app.key'));

        // dump(hash_equals($signature, (string) $request->query('signature', ''))); 
        
        return $this->userService->firstStepResetPassword($request->route()->parameters()+$request->all());
    }
}
