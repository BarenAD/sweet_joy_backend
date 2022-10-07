<?php

namespace App\Http\Requests\Shops;

use App\Policies\ShopPolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexShopRequest extends FormRequest
{
    public function authorize(ShopPolicy $shopPolicy)
    {
        return $shopPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
