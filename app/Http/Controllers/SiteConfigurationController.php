<?php

namespace App\Http\Controllers;

use App\Exceptions\NoReportException;
use App\Http\Requests\SiteConfigurations\SiteConfigurationIndexRequest;
use App\Http\Requests\SiteConfigurations\SiteConfigurationUpdateRequest;
use App\Models\SiteConfiguration;
use App\Repositories\SiteConfigurationRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

/**
 * Class SiteConfigurationController
 * @package App\Http\Controllers
 */
class SiteConfigurationController extends Controller
{
    private SiteConfigurationRepository $siteConfigurationRepository;

    public function __construct(
        SiteConfigurationRepository $siteConfigurationsRepository
    ) {
        $this->siteConfigurationRepository = $siteConfigurationsRepository;
    }

    public function data()
    {
        return response(
            Cache::tags(['site_configurations'])->remember('configurations', config('cache.timeout'), function () {
                $result = $this->siteConfigurationRepository->getAll()->keyBy('identify');
                if (config('app.demo_mode')) {
                    $result['demo_mode'] = [
                        'name' => 'Демонстрационный режим',
                        'identify' => 'demo_mode',
                        'value' => '1'
                    ];
                    $result['demo_user_email'] = [
                        'name' => 'Демонстрационны email администратора',
                        'identify' => 'demo_user_email',
                        'value' => env('DEMO_USER_EMAIL', 'admin@gmail.com'),
                    ];
                    $result['demo_user_password'] = [
                        'name' => 'Демонстрационны password администратора',
                        'identify' => 'demo_user_password',
                        'value' => env('DEMO_USER_PASSWORD', 'qwerty'),
                    ];
                }
                return $result;
            }),
        200);
    }

    public function index(SiteConfigurationIndexRequest $request): Response
    {
        return response($this->siteConfigurationRepository->getAll(), 200);
    }

    public function show(SiteConfigurationIndexRequest $request, int $id): Response
    {
        return response($this->siteConfigurationRepository->find($id), 200);
    }

    public function update(SiteConfigurationUpdateRequest $request, int $id): Response
    {
        if (config('app.demo_mode')) {
            throw new NoReportException('forbidden_by_demo');
        }
        return response($this->siteConfigurationRepository->update($id, $request->validated()), 200);
    }
}
