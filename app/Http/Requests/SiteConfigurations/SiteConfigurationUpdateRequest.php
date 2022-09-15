<?php

namespace App\Http\Requests\SiteConfigurations;

use Illuminate\Foundation\Http\FormRequest;

class SiteConfigurationUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'value' => 'string',
        ];
    }
}
