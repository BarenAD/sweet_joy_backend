<?php

namespace App\Http\Requests\Categories;

use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Http\FormRequest;

class CategoryDestroyRequest extends FormRequest
{
    public function authorize(CategoryPolicy $categoryPolicy)
    {
        return $categoryPolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
