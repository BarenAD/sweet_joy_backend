<?php


namespace Tests\Feature;


use App\Http\Requests\SiteConfigurations\SiteConfigurationUpdateRequest;
use App\Models\SiteConfiguration;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tests\TestApiResource;

class SiteConfigurationTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.configurations.site';
        $this->model = new SiteConfiguration();
        $this->only = ['index', 'show', 'update'];
        $this->formRequests = [
            'update' => SiteConfigurationUpdateRequest::class,
        ];
    }
}
