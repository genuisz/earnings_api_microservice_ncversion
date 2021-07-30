<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAddressBookRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteAddressBookRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\GetUserInfoRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class UserController extends Controller
{
    //
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }

    public function register(RegisterRequest $request){
        $request->ip = $request->ip();
        $this->userService->createUser($request);
    }

    public function updateUserInfo(UpdateUserInfoRequest $request){
        return $this->userService->updateUserInfo($request);
    }

    public function updateUserPassword(ChangePasswordRequest $request){
        return $this->userService->updateUserPassword($request);
    }
    public function getUserInfo(GetUserInfoRequest $request){
        return $this->userService->getUserInfo($request['id'],$request->header('Accept-Language','en'));
    }

    public function createOrUpdateAddressBook(AddAddressBookRequest $request){
        return $this->userService->createOrUpdateAddressBook($request);
    }

    public function deleteAddressBook(DeleteAddressBookRequest $request){
        return $this->userService->deleteAddressBook($request);

    }
    public function ping(Request $request){
        return response()->json('ping ed');
    }

    public function verifyUser(Request $request){
        return $this->userService->verifyUser(auth('jwt')->user()->id);
    }

    public function indexUser(Request $request){
        return $this->userService->indexUser($request);
    }


}
