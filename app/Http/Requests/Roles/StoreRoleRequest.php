<?php

namespace App\Http\Requests\Roles;

use App\Policies\RolePolicy;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(RolePolicy $rolePolicy)
    {
        return $rolePolicy->canStore();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ];
    }
}
