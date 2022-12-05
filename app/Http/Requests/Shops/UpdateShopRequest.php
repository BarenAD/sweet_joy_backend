<?php

namespace App\Http\Requests\Shops;

use App\Policies\ShopPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    public function authorize(ShopPolicy $shopPolicy)
    {
        return $shopPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'schedule_id' => 'required|numeric',
            'address' => 'required|string|max:255',
            'phone' => 'required|regex:/^[7]\d{10}$/',
            'map_integration' => 'string',
        ];
    }
}
