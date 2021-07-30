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
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProductService {

    use ApiRequest;
    public function __construct()
    {
        $this->baseUri = env('SERVICE_ORDER_BASE_URL');
        $this->secret = "";
    }

    public function listProduct(Request $request){
        //$this->performGetRequest()
    }


    public function getCategoryList(Request $request){
        try{
            $res =$this->requestAsync('GET',env('GET_CATEGORY_URL'),[],['Accept-Language'=>$request->header('Accept-Language','en')],null);
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }

    public function getFilterProductList(Request $request){
        try{
            $res =$this->requestAsync('GET',env('GET_PRODUCT_FILTER'),[],['Accept-Language'=>$request->header('Accept-Language','en')],null);
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }

    



    


    }



