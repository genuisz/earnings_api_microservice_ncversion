<?php

namespace App\Http\Requests;

use App\Exceptions\InputDataErrorException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Exceptions\HttpReponseInputErrorException;
class CreateProductRequest extends FormRequest
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


        return $this->getCustomRules($this->input('product_json'));
    }

    public function getCustomRules($class){

        $rules=[];
        if($class==null){
            $rules=[
                'category_id'=>'required',
                'factory_id'=>'required',
                'from_port_id'=>'required',
                'name_en'=>'required',
                'name_cn'=>'required',
                'name_zh'=>'required',
                'description'=>'required|max:512',
                'quantity_per_log'=>'required|numeric',
                'quantity_of_log'=>'required|numeric',
                'quantity_of_log_total'=>'required|numeric',
                'quantity_of_log_inventory_limit'=>'required|numeric',
                'quantity_unit_id'=>'required',
                'quantity_reach_target_in_log'=>'required',
                'price'=>'required',
                'log_weight'=>'required',
                'product_status_type_id'=>'required',
                'tolerance'=>'required',
                'leadtime'=>'required',
                'downpayment_ratio'=>'required',
                'deposit_ratio'=>'required',
                'duedate'=>'required',
                'image_url'=>'required',

            ];
        }
        else{
            $json = json_decode($this->product_json,true);
            for($i =0;$i<count($json);$i++){
                $rules["{$i}.category_id"] = 'required';
                $rules["{$i}.factory_id"] = 'required';
                $rules["{$i}.from_port_id"] = 'required';
                $rules["{$i}.name_en"] = 'required';
                $rules["{$i}.name_cn"] = 'required';
                $rules["{$i}.name_zh"] = 'required';
                $rules["{$i}.description"] = 'required|max:512';
                $rules["{$i}.quantity_per_log"] = 'required|numeric';
                $rules["{$i}.quantity_of_log"] = 'required|numeric';
                $rules["{$i}.quantity_of_log_total"] = 'required|numeric';
                $rules["{$i}.quantity_of_log_inventory_limit"] = 'required|numeric';
                $rules["{$i}.quantity_unit_id"] = 'required';
                $rules["{$i}.quantity_reach_target_in_log"] = 'required';
                $rules["{$i}.price"] = 'required';
                $rules["{$i}.log_weight"] = 'required';
                $rules["{$i}.product_status_type_id"] = 'required';
                $rules["{$i}.tolerance"] = 'required';
                $rules["{$i}.leadtime"] = 'required';
                $rules["{$i}.downpayment_ratio"] = 'required';
                $rules["{$i}.deposit_ratio"] = 'required';
                $rules["{$i}.duedate"] = 'required';
                $rules["{$i}.image_url"] = 'required';


                

                
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
