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

class OrderService {

    use ApiRequest;
    public function __construct()
    {
        $this->baseUri = env('SERVICE_ORDER_BASE_URL');
        $this->secret = "";
    }
    

    public function getMyOrder(Request $request){

        return $this->performGetRequestAuth($request);
    }

    public function getMyOrderDetails(Request $request){
        return $this->performPostRequestAuth($request);
    }
    public function createOrder(Request $request){
        return $this->performPostRequestAuth($request);
    }

    public function addToCart(Request $request){
        return $this->performPostRequestAuth($request);
    }

    public function getFullCart(Request $request){
        try{
            $res = $this->performRequest(null,'GET',env('GET_FULL_CART_URL'),$request->all());
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }

    public function getCart(Request $request){
        try{
            $res = $this->requestAsync('GET',env('GET_CART_URL'),[],['Accept-Language'=>$request->header('Accept-Language','en')],$request->header('authorization'));
            
        }
        catch(Exception $e){

            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }

    public function getOrderSnippet(Request $request){
        try{
            $res = $this->requestAsync('GET',env('GET_ORDER_SNIPPET_URL'),[],['Accept-Language'=>$request->header('Accept-Language','en')],$request->header('authorization'));
        }
        catch(Exception $e){

            throw new ExternalServiceConnectionException($e->getMessage());

        }
        return $res;

    }


   

    


    }



