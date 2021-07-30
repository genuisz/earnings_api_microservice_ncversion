<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\BackendUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Service\BackendUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use \Laravel\Passport\Http\Controllers\AccessTokenController as ATC;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Service\UserService;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    public $api_response = null;
    protected $backendUserService;
    protected $userService;
    public function __construct(BackendUserService $backendUserService,UserService $userService,Request $request)
    {
        $this->backendUserService = $backendUserService;
        $this->userService  = $userService;


    }




    public function test(){
        dd('i am developer');
        //$this->backendUserService->test();
    }


    protected function attemptLogin(LoginRequest $request)

    {
        return $this->userService->clientLogin($request);
    }

    protected function attemptLoginAdmin(LoginRequest $request){
        return $this->backendUserService->adminLogin($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return response([$this->username() => trans('auth.failed')], 401);
    }
    protected function firstStepPassword(Request $request){
        return $this->userService->firstStepPassword($request);
    }

    protected function resetPassword(Request $request){
        return $this->userService->resetPassword($request);
    }

    protected function emailVerify(Request $request){
        return $this->userService->emailVerify($request);
    }


    protected function testClient(Request $request){
        if($request->user()->can('test')){
            dd('client');
        }
        else{
            dd('cant');
        }
      
    }


    


}
