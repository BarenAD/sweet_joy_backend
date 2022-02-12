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
    private SiteConfigurationsService $siteConfigurationsService;

    public function __construct(SiteConfigurationsService $siteConfigurationsService)
    {
        $this->siteConfigurationsService = $siteConfigurationsService;
    }

    public function getSiteConfigurations(Request $request, int $id = null)
    {
        return response($this->siteConfigurationsService->getSiteConfigurations($id), 200);
    }

    public function changeSiteConfigurations(ChangeSiteConfiguration $request, int $id)
    {
        return response($this->siteConfigurationsService->changeSiteConfigurations(
            $request->user(),
            $id,
            $request->get('value')
        ), 200);
    }
}
