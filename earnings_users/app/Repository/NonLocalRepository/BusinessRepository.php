<?php
namespace App\Repository\NonLocalRepository;

use App\Traits\ApiRequest;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\ExternalServiceConnectionException;
use App\Repository\Interfaces\BusinessNatureRepositoryInterface;
use App\Repository\Interfaces\CountryRepositoryInterface;

class BusinessRepository implements BusinessNatureRepositoryInterface {
use ApiRequest;
    public function __construct()
    {
        $this->baseUri = env('SERVICE_ORDER_BASE_URL');
        $this->secret = "";
    }

    public function listProduct(){
        //$this->performGetRequest()
    }


    public function getBusiness($businessId,$language){

        try{
            $res =$this->requestAsync('GET',env('GET_BUSINESS'),array('business_id'=>$businessId),array('Accept-Language'=>$language),null);
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }
}