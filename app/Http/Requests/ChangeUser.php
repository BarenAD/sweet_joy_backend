<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUser extends FormRequest
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
            'fio' => 'string|min:1|max:255',
            'login' => 'string|min:1|max:255',
            'password' => 'string|min:1|max:255',
            'email' => 'string|min:1|max:255',
            'email_verified_at' => 'string|min:1|max:255',
            'phone' => 'regex:/^[7]\d{10}$/',
            'note' => 'string|min:1|max:255',
        ];
    }
}
