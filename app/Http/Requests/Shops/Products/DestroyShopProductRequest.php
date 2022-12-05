<?php

namespace App\Http\Requests\Shops\Products;

use App\Policies\ShopProductPolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyShopProductRequest extends FormRequest
{
    public function authorize(ShopProductPolicy $shopProductPolicy)
    {
        return $shopProductPolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
