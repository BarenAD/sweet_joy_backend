<?php

namespace App\Http\Requests\Permissions;

use App\Policies\PermissionPolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexPermissionRequest extends FormRequest
{
    public function authorize(PermissionPolicy $permissionPolicy)
    {
        return $permissionPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
