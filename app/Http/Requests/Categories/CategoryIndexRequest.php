<?php

namespace App\Http\Requests\Categories;

use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Http\FormRequest;

class CategoryIndexRequest extends FormRequest
{
    public function authorize(CategoryPolicy $categoryPolicy)
    {
        return $categoryPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
