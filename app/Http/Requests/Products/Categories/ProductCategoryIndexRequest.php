<?php

namespace App\Http\Requests\Products\Categories;

use App\Policies\ProductCategoryPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryIndexRequest extends FormRequest
{
    public function authorize(ProductCategoryPolicy $productCategoryPolicy)
    {
        return $productCategoryPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
