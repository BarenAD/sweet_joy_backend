<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeOrCreateItem extends FormRequest
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
            'id' => 'numeric',
            'picture' => 'image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|string|max:255',
            'composition' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'product_unit' => 'required|string|max:255',
            'categories_item' => 'required|array',
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
