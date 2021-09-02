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
    private $siteConfigurationsRepository;

    /**
     * SiteConfigurationsService constructor.
     * @param SiteConfigurationsRepository $siteConfigurationsRepository
     */
    public function __construct(SiteConfigurationsRepository $siteConfigurationsRepository)
    {
        $this->siteConfigurationsRepository = $siteConfigurationsRepository;
    }

    /**
     * @param int|null $id
     * @return \App\Models\SiteConfigurations[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getSiteConfigurations(int $id = null) {
        return $this->siteConfigurationsRepository->getSiteConfigurations($id);
    }

    /**
     * @param User $user
     * @param int $id
     * @param string|null $value
     * @return \App\Models\SiteConfigurations[]|\Illuminate\Database\Eloquent\Collection
     */
    public function changeSiteConfigurations(
        User $user,
        int $id,
        string $value = null
    ) {
        $siteConfiguration = $this->siteConfigurationsRepository->getSiteConfigurations($id);
        if (SiteConfigurationsPolicy::canUpdate($user)) {
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
