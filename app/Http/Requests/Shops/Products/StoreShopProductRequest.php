<?php

namespace App\Http\Requests\Shops\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
