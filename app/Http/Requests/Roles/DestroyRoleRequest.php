<?php

namespace App\Http\Requests\Roles;

use App\Policies\RolePolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRoleRequest extends FormRequest
{
    public function authorize(RolePolicy $rolePolicy)
    {
        return $rolePolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
