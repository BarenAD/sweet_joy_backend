<?php

namespace App\Http\Requests\Users;

use App\Policies\UserPolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function authorize(UserPolicy $userPolicy)
    {
        return $userPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
