<?php

namespace App\Http\Requests\Shops\Products;

use App\Policies\ShopProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class StoreShopProductRequest extends FormRequest
{
    public function authorize(ShopProductPolicy $shopProductPolicy)
    {
        return $shopProductPolicy->canStore();
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
