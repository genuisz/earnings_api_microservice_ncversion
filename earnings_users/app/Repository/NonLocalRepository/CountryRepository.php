<?php
namespace App\Repository\NonLocalRepository;

use App\Traits\ApiRequest;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\ExternalServiceConnectionException;
use App\Repository\Interfaces\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface {
use ApiRequest;
    public function __construct()
    {
        $this->baseUri = env('SERVICE_ORDER_BASE_URL');
        $this->secret = "";
    }

    public function listProduct(){
        //$this->performGetRequest()
    }


    public function getCountryDetails($language,$country_id){

        try{
            $res =$this->requestAsync('GET',env('GET_COUNTRY_DETAILS'),array('country_id'=>$country_id),array('Accept-Language'=>$language),null);
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;

        
    }
}