<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteConfigurations\SiteConfigurationIndexRequest;
use App\Http\Requests\SiteConfigurations\SiteConfigurationUpdateRequest;
use App\Repositories\SiteConfigurationRepository;
use Illuminate\Http\Response;

/**
 * Class SiteConfigurationController
 * @package App\Http\Controllers
 */
class SiteConfigurationController extends Controller
{
    private SiteConfigurationRepository $siteConfigurationsRepository;

    public function __construct(
        SiteConfigurationRepository $siteConfigurationsRepository
    ) {
        $this->siteConfigurationsRepository = $siteConfigurationsRepository;
    }

    public function index(SiteConfigurationIndexRequest $request): Response
    {
        return response($this->siteConfigurationsRepository->getAll(), 200);
    }

    public function show(SiteConfigurationIndexRequest $request, int $id): Response
    {
        return response($this->siteConfigurationsRepository->find($id), 200);
    }

    public function update(SiteConfigurationUpdateRequest $request, int $id): Response
    {
        return response($this->siteConfigurationsRepository->update($id, $request->validated()), 200);
    }
}
