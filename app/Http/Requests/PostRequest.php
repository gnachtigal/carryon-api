<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
             'title' => 'required',
             'body' => 'required|min:50',
             'user_id' => 'required|integer',
         ];
     }

     public function messages(){
         return [
            'title.required' => 'Você precisa inserir um título',
            'body.min:50' => 'Você precisa escrever pelo menos 50 caracteres',
            'body.required' => 'Você precisa escrever uma descrição',
        ];
     }
}
