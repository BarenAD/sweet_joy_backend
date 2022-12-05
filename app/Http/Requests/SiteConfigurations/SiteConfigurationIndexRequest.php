<?php

namespace App\Http\Requests\SiteConfigurations;

use App\Policies\SiteConfigurationPolicy;
use Illuminate\Foundation\Http\FormRequest;

class SiteConfigurationIndexRequest extends FormRequest
{
    public function authorize(SiteConfigurationPolicy $siteConfigurationPolicy)
    {
        return $siteConfigurationPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
