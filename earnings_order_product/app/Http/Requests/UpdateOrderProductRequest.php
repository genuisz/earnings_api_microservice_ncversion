<?php

namespace App\Http\Requests;

use App\Exceptions\InputDataErrorException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Exceptions\HttpReponseInputErrorException;
class UpdateOrderProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * 
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //dd($this->input('product_json'));
        // return [
        //     //
        //     'email'=> 'bail|required|unique:users|email',
        //     'username'=>['bail','required','unique:users','min:5','max:20','regex:/^[a-zA-Z0-9_]*(?!.*[\s])$/'],
        //     'password'=>['required','min:8','max:255','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*(_|[^\w])).+$/'],
        //     'name'=>'required|max:60|regex:/^[a-zA-Z ]+$/',
        //     'delivery_address1'=>'max:220',
        //     'delivery_address2'=>'max:220',
        //     'contact_no'=>'required|numeric',
        //     'country_id'=>'required|numeric',
        //     'business_nature_id'=>'required|numeric',
        //     'interested_category'=>'required',
        //     'company_website'=>'url',
        //     'gender'=>'in:M,F'




        // ];


        return $this->getCustomRules($this->input('order_product_json'));
    }

    public function getCustomRules($class){

        $rules=[];
        if($class==null){
            $rules=[
                'order_transact_id'=>'required',
                'product_id'=>'required',
                'logistic_cost'=>'prohibited',
                'downpayment'=>'prohibited',
                'deposit'=>'prohibited',
                'total'=>'prohibited',
                'unique_order_product_id'=>'prohibited'
            ];
        }
        else{
            $json = json_decode($this->order_product_json,true);
            for($i =0;$i<count($json);$i++){
                $rules["{$i}.order_transact_id"] = 'required';
                $rules["{$i}.product_id"] = 'required';
                $rules["{$i}.logistic_cost"] = 'prohibited';
                $rules["{$i}.downpayment"] = 'prohibited';
                $rules["{$i}.deposit"] = 'prohibited';
                $rules["{$i}.total"] = 'prohibited';
                $rules["{$i}.unique_order_product_id"] = 'prohibited';

                
            }
            
        }
       
        return $rules;

    }

    public function validationData()
    {
        if($this->input('order_product_json')==null){
            return $this->all();
        }
        $json = json_decode($this->order_product_json,true);
        
        return $json;
        
    }

    protected function failedValidation(Validator $validator){
        
        $responseData = $validator->errors();
        
        throw (new HttpReponseInputErrorException("",$responseData));
    }






}
