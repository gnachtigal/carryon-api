<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ];
    }

    public function messages(){
        return [
           'name.required' => 'O campo nome é obrigatório.',
           'email.required'  => 'O campo e-mail é obrigatório.',
           'password.required'  => 'O campo senha é obrigatório.',
           'c_password.same'  => 'O campo e-mail é obrigatório.',
   ];
    }
}
