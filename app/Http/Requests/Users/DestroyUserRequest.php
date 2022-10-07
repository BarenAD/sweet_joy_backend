<?php

namespace App\Http\Requests\Users;

use App\Policies\UserPolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyUserRequest extends FormRequest
{
    public function authorize(UserPolicy $userPolicy)
    {
        return $userPolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
