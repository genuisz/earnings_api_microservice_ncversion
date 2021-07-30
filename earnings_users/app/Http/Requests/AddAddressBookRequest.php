<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
class AddAddressBookRequest extends FormRequest
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
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'email'=>'required|email|max:255',
            'country'=>'required|string|max:255',
            'address_line1'=>'required|max:255',
            'address_line2'=>'max:255',
            'city'=>'required',
            'zip'=>'required',
            'state'=>'required',
            'phone'=>'required',
            'address_book_id'=>'numeric'
        ];




    }

    public function messages(){
        return [


            


        ];
    }



}
