<?php


namespace App\Repositories;


use App\Models\SiteConfigurations;

/**
 * Class SiteConfigurationsRepository
 * @package App\Repositories
 */
class SiteConfigurationsRepository
{
    private SiteConfigurations $model;

    public function __construct(SiteConfigurations $siteConfigurations)
    {
        $this->model = $siteConfigurations;
    }

    public function getSiteConfigurations(int $id = null)
    {
        if ($id) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }
}
