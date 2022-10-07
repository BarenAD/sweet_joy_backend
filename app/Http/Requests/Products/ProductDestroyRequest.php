<?php

namespace App\Http\Requests\Products;

use App\Policies\ProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ProductDestroyRequest extends FormRequest
{
    public function authorize(ProductPolicy $productPolicy)
    {
        return $productPolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
