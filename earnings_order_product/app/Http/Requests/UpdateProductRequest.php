<?php

namespace App\Http\Requests;

use App\Exceptions\InputDataErrorException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Exceptions\HttpReponseInputErrorException;
class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * 
     */
    protected $type ='';
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


        return $this->getCustomRules($this->input('product_json'));
    }

    public function getCustomRules($class){

        $rules=[];
        if($class==null){
            $rules=[
                'id'=>'required|numeric',
                'quantity_of_log'=>'numeric',
                'quantity_per_log'=>'numeric',
                'quantity_of_log_total'=>'numeric',
                'quantity_of_log_inventory_limit'=>'numeric',
                
            ];
        }
        else{
            $json = json_decode($this->product_json,true);
            for($i =0;$i<count($json);$i++){
                $rules["{$i}.id"] = 'required';
            }
            
        }
       
        return $rules;

    }

    public function validationData()
    {
        if($this->input('product_json')==null){
            return $this->all();
        }
        $json = json_decode($this->product_json,true);
        
        return $json;
        
    }

    protected function failedValidation(Validator $validator){
        
        $responseData = $validator->errors();

        throw (new HttpReponseInputErrorException("",$responseData));
    }






}
