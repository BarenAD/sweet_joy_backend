<?php

namespace App\Http\Requests;

use App\Rules\NumericKeysArray;
use Illuminate\Foundation\Http\FormRequest;

class ChangeOrCreateAdminInfo extends FormRequest
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
            'ids_pos' => new NumericKeysArray('ids_pos'),
            'ids_pos.*.*' => 'numeric',
        ];
    }
}
