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
use Illuminate\Support\Facades\Cache;
use App\Service\UserService;
use Laravel\Ui\Presets\React;

class UserController extends Controller
{
    protected $backendUserService;
    protected $userService;
    public function __construct(BackendUserService $backendUserService,UserService $userService)
    {
        $this->backendUserService = $backendUserService;
        $this->userService  = $userService;
    }

    public function registerClient(Request $request){
        return $this->userService->register($request);
    }

    
}