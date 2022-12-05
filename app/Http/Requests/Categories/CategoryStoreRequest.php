<?php

namespace App\Http\Requests\Categories;

use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    public function authorize(CategoryPolicy $categoryPolicy)
    {
        return $categoryPolicy->canStore();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
