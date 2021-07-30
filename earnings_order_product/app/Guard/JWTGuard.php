<?php
namespace App\Guard;
use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWT;
class JWTGuard implements Guard
{
    use GuardHelpers;
    /**
     * @var JWT $jwt
     */
    protected  $jwt;
    /**
     * @var Request $request
     */
    protected  $request;

    protected $scope;
    /**
     * JWTGuard constructor.
     * @param JWT $jwt
     * @param Request $request
     */
    public function __construct(JWT $jwt, Request $request) {
        $this->jwt = $jwt;
        $this->request = $request;
    }
    public function user() {
        if (! is_null($this->user)) {
            return $this->user;
        }
        if ($this->jwt->setRequest($this->request)->getToken() && $this->jwt->check()) {

            $id = $this->jwt->payload()->get('sub');
            $role = $this->jwt->payload()->get('role');
            $permission = $this->jwt->payload()->get('permission');
            $type = $this->jwt->payload()->get('type');
            $this->user = new User();
            $this->user->id = $id;
            $this->user->role = $role;
            $this->user->permission = $permission;
            $this->user->type = $type;

            // Set data from custom claims
            return $this->user;
        }
        return null;
    }

    public function scope(){
     $this->scope = $this->jwt->payload()->toJson();
     return $this->scope;
    }
    public function validate(array $credentials = []) {
    }




    // DEV TEST//
    public function fakeTest(){
        $this->user = new User();
        $this->user->id = 1;
        // Set data from custom claims
        return $this->user;
    }
    // 
}