<?php

namespace App\Http\Requests\Users\Roles;

use App\Policies\UserRolePolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyUserRoleRequest extends FormRequest
{
    public function authorize(UserRolePolicy $userRolePolicy)
    {
        return $userRolePolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
