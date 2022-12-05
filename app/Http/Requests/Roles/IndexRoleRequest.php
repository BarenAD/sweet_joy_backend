<?php

namespace App\Http\Requests\Roles;

use App\Policies\RolePolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexRoleRequest extends FormRequest
{
    public function authorize(RolePolicy $rolePolicy)
    {
        return $rolePolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
