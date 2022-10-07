<?php

namespace App\Http\Requests\Shops\Products;

use App\Policies\ShopProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopProductRequest extends FormRequest
{
    public function authorize(ShopProductPolicy $shopProductPolicy)
    {
        return $shopProductPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'product_id' => 'required|numeric',
            'shop_id' => 'required|numeric',
            'price' => 'numeric',
            'count' => 'numeric',
        ];
    }
}
