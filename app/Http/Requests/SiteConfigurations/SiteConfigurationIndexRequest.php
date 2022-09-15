<?php

namespace App\Http\Requests\SiteConfigurations;

use Illuminate\Foundation\Http\FormRequest;

class SiteConfigurationIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}
