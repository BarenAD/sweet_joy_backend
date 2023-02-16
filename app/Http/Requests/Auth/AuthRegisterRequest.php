<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fio' => 'required|string|regex:/^\w{1,}\s\w{1,}\s\w{1,}$/iu',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|max:255',
            'phone' => 'required|regex:/^[7]\d{10}$/',
        ];
    }
}
