<?php

namespace App\Http\Requests\Products;

use App\Policies\ProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(ProductPolicy $productPolicy)
    {
        return $productPolicy->canStore();
    }

    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|string|max:255',
            'composition' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'product_unit' => 'required|string|max:255',
            'product_categories' => 'array',
            'product_categories.*' => 'numeric'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'product_categories' => json_decode($this->product_categories),
        ]);
    }
}
