<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;

class ChangeItem extends FormRequest
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
            'picture' => 'image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|string|max:255',
            'composition' => 'string|max:255',
            'manufacturer' => 'string|max:255',
            'description' => 'string|max:255',
            'product_unit' => 'string|max:255',
            'categories_item' => 'array',
            'categories_item.*' => 'numeric'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'categories_item' => json_decode($this->categories_item),
        ]);
    }
}
