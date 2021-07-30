<?php

namespace App\Http\Requests;

use App\Exceptions\InputDataErrorException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Exceptions\HttpReponseInputErrorException;
class indexOrderDetailsRequest extends FormRequest
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
        return [
            //

            'order'=>'required',
            'sort'=>'required',
            'offset'=>'required|numeric',
            'limit'=>'required|numeric',
            'user_id'=>'required_without:order_transact_id',
            'order_transact_id'=>'required_without:user_id'





        ];



    }



    protected function failedValidation(Validator $validator){
        
        $responseData = $validator->errors();
        
        throw (new HttpReponseInputErrorException("",$responseData));
    }






}
