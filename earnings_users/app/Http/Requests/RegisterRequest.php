<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
        return [
            //
            'email'=> 'bail|required|unique:users|email',
            'username'=>['bail','required','unique:users','min:5','max:20','regex:/^[a-zA-Z0-9_]*(?!.*[\s])$/'],
            'password'=>['required','min:8','max:255','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*(_|[^\w])).+$/'],
            'name'=>'required|max:60|regex:/^[a-zA-Z ]+$/',
            'delivery_address1'=>'max:220',
            'delivery_address2'=>'max:220',
            'contact_no'=>'required|numeric',
            'country_id'=>'required|numeric',
            'business_nature_id'=>'required|numeric',
            'interested_category'=>'required',
            'company_website'=>'url',
            'gender'=>'in:M,F'


        ];




    }

    public function messages(){
        return [
            'email.required'=>'Please Enter All Required Field',
            'username.required'=>'Please Enter All Required Field',
            'password.required'=>'Please Enter All Required Field',
            'name.required'=>'Please Enter All Required Field',
            'country_id.required'=>'Please Enter All Required Field',
            'contact_no.required'=>'Please Enter All Required Field',
            'business_nature_id.required'=>'Please Enter All Required Field',
            'interested_category.required'=>'Please Enter All Required Field',
            'username.regex'=>'The Username must include in alphabet and digit',
            'password.regex'=>'The Password must include at least 1 Uppercase alphabet,special Character and digit',
            'name.regex'=>'The name should only include alphabet or space'



        ];
    }

}
