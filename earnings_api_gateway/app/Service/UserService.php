<?php
namespace App\Service;

use App\Http\Requests\LoginRequest;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Traits\ApiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ExternalServiceConnectionException;
use App\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserService {

    use ApiRequest;
    use ApiResponser;

    public function __construct()
    {
        $this->baseUri = env('SERVICE_USER_BASE_URL');
        $this->secret = "";
    }
    public function clientLogin(LoginRequest $request)
    {

        $res = Route::dispatch(request()->create('oauth/token', 'POST', $request->all()));
        $this->api_response = json_decode($res->getContent());
        $param = $request->only(['username','password']);
        $credentials = array('email'=>$param['username'],'password'=>$param['password']);

        if (!$internalToken = auth('jwt')->attempt($credentials)) {
            abort(406);
        }
        // dump($internalToken);
        // dump($this->api_response);

        Cache::store('redis')->set(hash('sha512',$this->api_response->token_type.' '.$this->api_response->access_token),$internalToken,config('jwt.ttl')*60);
        return $this->successResponse($internalToken);
        // return $this->successResponse($this->api_response);
        // return $res->getStatusCode() === 200 ? true : false;
    }

    public function firstStepPassword(Request $request){

        return $this->performGetRequest($request);
    }

    public function resetPassword(Request $request){

        return $this->performPostRequest($request);
    }

    public function emailVerify(Request $request){
        return $this->performGetRequest($request);
    }

    public function register(Request $request){
        return $this->performPostRequest($request);
    }


    }



