<?php

namespace App\Http\Requests\Products;

use App\Policies\ProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(ProductPolicy $productPolicy)
    {
        return $productPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'image' => 'image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|string|max:255',
            'composition' => 'string|max:255',
            'manufacturer' => 'string|max:255',
            'description' => 'string|max:255',
            'product_unit' => 'string|max:255',
            'categories' => 'array',
            'categories.*' => 'numeric'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'categories' => json_decode($this->categories),
        ]);
    }
}
