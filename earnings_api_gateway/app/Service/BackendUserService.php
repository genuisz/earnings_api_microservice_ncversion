<?php 
namespace App\Service;

use App\Repository\Interfaces\BackendUserRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
class BackendUserService{

    use ApiResponser;

    protected $backendUserRepo;
    public function __construct(BackendUserRepositoryInterface $backendUserRepo )
    {
        $this->backendUserRepo  = $backendUserRepo;
    }

    public function test(){
        $this->backendUserRepo->test();
    }

    public function adminLogin(LoginRequest $request){
        $res = Route::dispatch(request()->create('oauth/token', 'POST', $request->all()));
        $this->api_response = json_decode($res->getContent());

        $param = $request->only(['username','password']);
        // $credentials = array('username'=>$param['username'],'password'=>$param['password']);
        if (!$internalToken = auth('jwt_admin')->attempt($param)) {
            abort(406);
        }
        // dump($internalToken);
        // dump($this->api_response);
        Cache::store('redis')->set(hash('sha512',$this->api_response->token_type.' '.$this->api_response->access_token),$internalToken,config('jwt.ttl'));
        return $this->successResponse($internalToken);
        // return $this->successResponse($this->api_response);
        // return $res->getStatusCode() === 200 ? true : false;
    }
}