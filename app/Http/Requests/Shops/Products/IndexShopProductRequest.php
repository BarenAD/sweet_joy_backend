<?php

namespace App\Http\Requests\Shops\Products;

use App\Policies\ShopProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexShopProductRequest extends FormRequest
{
    public function authorize(ShopProductPolicy $shopProductPolicy)
    {
        return $shopProductPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
