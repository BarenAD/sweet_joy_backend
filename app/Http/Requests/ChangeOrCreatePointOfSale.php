<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeOrCreatePointOfSale extends FormRequest
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
            'id_s' => 'required|numeric',
            'address' => 'required|string|max:255',
            'phone' => 'required|regex:/^[7]\d{10}$/',
            'map_integration' => 'string',
        ];
    }
}
