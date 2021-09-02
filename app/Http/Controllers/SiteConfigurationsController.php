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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getSiteConfigurations(Request $request)
    {
        return response($this->siteConfigurationsService->getSiteConfigurations($request->get('id')), 200);
    }

    /**
     * @param ChangeSiteConfiguration $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeSiteConfigurations(ChangeSiteConfiguration $request) {
        return response($this->siteConfigurationsService->changeSiteConfigurations(
            $request->user(),
            (int) $request->get('id'),
            (int) $request->get('value')
        ), 200);
    }
}
