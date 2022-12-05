<?php

namespace App\Http\Requests\Roles\Permissions;

use App\Policies\RolePermissionPolicy;
use Illuminate\Foundation\Http\FormRequest;

class StoreRolePermissionRequest extends FormRequest
{
    public function authorize(RolePermissionPolicy $rolePermissionPolicy)
    {
        return $rolePermissionPolicy->canStore();
    }

    public function rules()
    {
        return [
            'permission_id' => 'required|numeric',
        ];
    }
}
