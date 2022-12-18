<?php


namespace Tests\Feature;


use App\Http\Requests\SiteConfigurations\SiteConfigurationUpdateRequest;
use App\Models\SiteConfiguration;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
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

    public function testData()
    {
        Config::set('app.demo_mode', true);
        $params = $this->seedsBD();
        $preparedParams = [];
        foreach ($params as $value) {
            $preparedParams[$value['identify']] = $value;
        }
        $preparedParams['demo_mode'] = '1';
        $preparedParams['demo_user_email'] = env('DEMO_USER_EMAIL', 'admin@admin.ru');
        $preparedParams['demo_user_password'] = env('DEMO_USER_PASSWORD', 'qwerty');
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('site_configurations.data'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $preparedParams);
    }

    public function testUpdateByDemo()
    {
        Config::set('app.demo_mode', true);
        $params = $this->seedsBD();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->put(
                route('management.configurations.site.update', $params[0]['id']),
                ['value' => 'test']
            );
        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
        $responseJson = $response->json();
        $this->assertTrue($responseJson['id'] === config('exceptions.forbidden_by_demo.id'));
    }
}
