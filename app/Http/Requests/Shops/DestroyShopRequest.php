<?php

namespace App\Http\Requests\Shops;

use App\Policies\ShopPolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyShopRequest extends FormRequest
{
    public function authorize(ShopPolicy $shopPolicy)
    {
        return $shopPolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
