<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeSiteConfiguration;
use App\Http\Services\SiteConfigurationsService;
use Illuminate\Http\Request;

/**
 * Class SiteConfigurationsController
 * @package App\Http\Controllers
 */
class SiteConfigurationsController extends Controller
{
    private $siteConfigurationsService;

    /**
     * SiteConfigurationsController constructor.
     * @param SiteConfigurationsService $siteConfigurationsService
     */
    public function __construct(SiteConfigurationsService $siteConfigurationsService)
    {
        $this->siteConfigurationsService = $siteConfigurationsService;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getSiteConfigurations(Request $request, int $id = null)
    {
        return response($this->siteConfigurationsService->getSiteConfigurations($id), 200);
    }

    /**
     * @param ChangeSiteConfiguration $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeSiteConfigurations(ChangeSiteConfiguration $request, int $id)
    {
        return response($this->siteConfigurationsService->changeSiteConfigurations(
            $request->user(),
            $id,
            $request->get('value')
        ), 200);
    }
}
