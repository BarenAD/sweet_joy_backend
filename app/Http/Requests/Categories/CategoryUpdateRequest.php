<?php

namespace App\Http\Requests\Categories;

use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(CategoryPolicy $categoryPolicy)
    {
        return $categoryPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
