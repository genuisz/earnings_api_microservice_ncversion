<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Exception;
use App\Exceptions\ExternalServiceConnectionException;
use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Cache;
trait ApiRequest
{
    /**
     * Send request to any service
     * @param $method
     * @param $requestUrl
     * @param array $formParams
     * @param array $headers
     * @return string
     */
    public function performRequest($token,$method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri'  =>  $this->baseUri,
        ]);
        if($token!=null){
            $this->secret = $token;
        }

        if(isset($this->secret))
        {
            $headers['Authorization'] = 'Bearer '.$this->secret;
        }
        if($method=='GET'){
            $response = $client->request($method, $requestUrl,[
                'query'=> $formParams,
                'headers'=>$headers
            ]);
        }
        else{
            $response = $client->request($method, $requestUrl, [
                'form_params' => $formParams,
                'headers'     => $headers,
            ]);
        }

        return $response->getBody()->getContents();
    }

    public function performPostRequestAuth($request){
        //dd(Cache::store('redis')->get($request->header('authorization')));
        try{
            $res = $this->performRequest(Cache::store('redis')->get(hash('sha512',$request->header('authorization'))),'POST',$request->server->get('REQUEST_URI')."",$request->all());
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }

    public function performGetRequestAuth($request){

        try{
            $this->secret =Cache::store('redis')->get(hash('sha512',$request->header('authorization')));
            $res = $this->performRequest('GET',$request->server->get('REQUEST_URI')."",$request->all());
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }
    public function performPostRequest($request){
        try{
            $this->secret =Cache::store('redis')->get(hash('sha512',$request->header('authorization')));
            $res = $this->performRequest(null,'POST',$request->server->get('REQUEST_URI')."",$request->all());
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }

    public function performGetRequest($request){

        try{
            $res = $this->performRequest(null,'GET',$request->server->get('REQUEST_URI')."",$request->all());
            
        }
        catch(Exception $e){
            
            throw new ExternalServiceConnectionException($e->getMessage());
        }
        return $res;
    }

    public function performAsyncRequest($promises,$await){
        //dd(hash('sha512',$token));


        if(isset($this->secret))
        {
            $headers['Authorization'] = $this->secret;
        }


        
        if($await){
            $response = Promise\Utils::unwrap($promises);

        }
        else{
            $response = Promise\Utils::settle($promises)->wait();
        }
    
        return $response;


        
    }

    public function requestAsync($method,$url,$formParams=[],$headers=[],$token){

        $client = new Client([
            'base_uri'  =>  $this->baseUri,
        ]);
        if($token!=null){
            $internalToken = Cache::store('redis')->get(hash('sha512',$token));
            if($internalToken!=null){
                $this->secret = 'Bearer '.$internalToken;
               
                
                
            }
            else{
                throw new ExternalServiceConnectionException('internal token fail');
            }
        }

        if(isset($this->secret))
        {
            $headers['Authorization'] = $this->secret;
        }    

        if($method=='GET'){
            $response =  $client->requestAsync($method,$url,
                [
                    'query'=> $formParams,
                    'headers'=>$headers
                ]
            );

        }
        else{
            $response = $client->requestAsync($method, $url,[
                'form_params' => $formParams,
                'headers'=>$headers
            ]);
        }
        return $response;
        
    }
}