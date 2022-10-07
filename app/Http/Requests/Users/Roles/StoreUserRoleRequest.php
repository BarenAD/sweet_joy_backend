<?php

namespace App\Http\Requests\Users\Roles;

use App\Policies\UserRolePolicy;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRoleRequest extends FormRequest
{
    public function authorize(UserRolePolicy $userRolePolicy)
    {
        return $userRolePolicy->canStore();
    }

    public function rules()
    {
        return [
            'role_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ];
    }
}
