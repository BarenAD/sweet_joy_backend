<?php

namespace App\Http\Requests\Users\Roles;

use App\Policies\UserRolePolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexUserRoleRequest extends FormRequest
{
    public function authorize(UserRolePolicy $userRolePolicy)
    {
        return $userRolePolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
