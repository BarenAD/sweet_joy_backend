<?php

namespace App\Http\Requests\Products;

use App\Policies\ProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    public function authorize(ProductPolicy $productPolicy)
    {
        return $productPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
