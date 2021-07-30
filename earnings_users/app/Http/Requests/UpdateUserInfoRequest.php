<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UpdateUserInfoRequest extends FormRequest
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
            'name'=>'required',
            'delivery_address1'=>'required',
            'delivery_address2'=>'required',
            'contact_no'=>'required|numeric',
            'password'=>'required'


        ];
    }

    public function messages(){
        return [
            'name_en.required'=>'please enter name',
            'name_zh.required'=>'please enter name',
            'name_en.required'=>'please enter name',
            'delivery_address1.required'=>'please enter address',
            'delivery_address2.required'=>'please enter address',
            'contact_no.required'=>'please enter tel',
            'contact_no.numeric'=>'please enter correct phone number',
            'password.required'=>'please enter password'

        ];
    }

    public function withValidator($validator){
        $validator->after(function($validator){

            if(!Hash::check($this->password,$this->user()->password)){
                $validator->errors()->add('current_password', 'Your current password is incorrect.');
            }
        });
        return ;
    }

}
