<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class ChangePasswordRequest extends FormRequest
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
            'current_password'=>'required',
            'new_password'=>['required','min:8','max:255','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*(_|[^\w])).+$/']


        ];




    }

    public function messages(){
        return [
            'new_password.regex'=>'The Password must include at least 1 Uppercase alphabet,special Character and digit '
        ];
    }

    public function withValidator($validator){

        $validator->after(function($validator){
            
            if(!Hash::check($this->current_password,$this->user()->find($this->user()->id)->password)){
                $validator->errors()->add('current_password', 'Your current password is incorrect.');
            }
        });
        return ;
    }

}
