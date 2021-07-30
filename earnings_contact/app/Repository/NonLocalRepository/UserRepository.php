<?php
namespace App\Repository\NonLocalRepository;

use App\Exceptions\ExternalServiceConnectionException;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Traits\ApiRequest;
use GuzzleHttp\Exception\BadResponseException;

class UserRepository implements UserRepositoryInterface{

    public $baseUri;
    public $secret;
    use ApiRequest;
    public function __construct()
    {
        $this->baseUri = env('SERVICE_USER_BASE_URL');
        // $this->secret = config('services.user.secret');
        $this->secret = "";
    }
    public function verifyUser($id)
    {
        $data['id'] = $id;
        try{
            $res = $this->performRequest('POST','/api/verifyEmail',$data);
            
        }
        catch(BadResponseException $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        
        return $res;
    }
}