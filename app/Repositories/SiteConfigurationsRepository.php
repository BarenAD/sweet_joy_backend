<?php


namespace App\Repositories;


use App\Models\SiteConfigurations;

/**
 * Class SiteConfigurationsRepository
 * @package App\Repositories
 */
class SiteConfigurationsRepository
{
    private $model;

    /**
     * SiteConfigurationsRepository constructor.
     * @param SiteConfigurations $siteConfigurations
     */
    public function __construct(SiteConfigurations $siteConfigurations)
    {
        $this->model = $siteConfigurations;
    }

    /**
     * @param int|null $id
     * @return SiteConfigurations[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getSiteConfigurations(int $id = null)
    {
        if ($id) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }
}
