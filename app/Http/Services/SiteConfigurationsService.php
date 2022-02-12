<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:00
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\SiteConfigurationsPolicy;
use App\Repositories\SiteConfigurationsRepository;

/**
 * Class SiteConfigurationsService
 * @package App\Http\Services
 */
class SiteConfigurationsService
{
    private SiteConfigurationsRepository $siteConfigurationsRepository;
    private SiteConfigurationsPolicy $siteConfigurationsPolicy;

    public function __construct(
        SiteConfigurationsRepository $siteConfigurationsRepository,
        SiteConfigurationsPolicy $siteConfigurationsPolicy
    ){
        $this->siteConfigurationsRepository = $siteConfigurationsRepository;
        $this->siteConfigurationsPolicy = $siteConfigurationsPolicy;
    }

    public function getSiteConfigurations(int $id = null)
    {
        return $this->siteConfigurationsRepository->getSiteConfigurations($id);
    }

    public function changeSiteConfigurations(
        User $user,
        int $id,
        string $value = null
    ) {
        $siteConfiguration = $this->siteConfigurationsRepository->getSiteConfigurations($id);
        if ($this->siteConfigurationsPolicy->canUpdate($user)) {
            $siteConfiguration->fill([
                'value' => $value,
            ])->save();
            CacheService::cacheProductsInfo('delete', 'site_configurations');
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return $siteConfiguration;
    }
}
