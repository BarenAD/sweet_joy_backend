<?php

namespace App\Http\Requests\Users;

use App\Policies\UserPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(UserPolicy $userPolicy)
    {
        return $userPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'fio' => 'required|string|max:255',
            'login' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'string|max:255',
            'phone' => 'required|regex:/^[7]\d{10}$/',
        ];
    }
}
