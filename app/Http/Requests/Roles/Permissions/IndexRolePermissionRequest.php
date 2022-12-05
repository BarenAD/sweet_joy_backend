<?php

namespace App\Http\Requests\Roles\Permissions;

use App\Policies\RolePermissionPolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexRolePermissionRequest extends FormRequest
{
    public function authorize(RolePermissionPolicy $rolePermissionPolicy)
    {
        return $rolePermissionPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
