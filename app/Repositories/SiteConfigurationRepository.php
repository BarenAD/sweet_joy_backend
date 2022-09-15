<?php


namespace App\Repositories;


use App\Models\SiteConfiguration;

/**
 * Class SiteConfigurationRepository
 * @package App\Repositories
 */
class SiteConfigurationRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return SiteConfiguration::class;
    }
}
