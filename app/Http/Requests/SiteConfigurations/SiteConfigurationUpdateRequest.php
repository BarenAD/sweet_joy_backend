<?php

namespace App\Http\Requests\SiteConfigurations;

use App\Policies\SiteConfigurationPolicy;
use Illuminate\Foundation\Http\FormRequest;

class SiteConfigurationUpdateRequest extends FormRequest
{
    public function authorize(SiteConfigurationPolicy $siteConfigurationPolicy)
    {
        return $siteConfigurationPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'value' => 'string',
        ];
    }
}
