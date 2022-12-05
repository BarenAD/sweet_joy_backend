<?php

namespace App\Http\Requests\Roles\Permissions;

use App\Policies\RolePermissionPolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRolePermissionRequest extends FormRequest
{
    public function authorize(RolePermissionPolicy $rolePermissionPolicy)
    {
        return $rolePermissionPolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
