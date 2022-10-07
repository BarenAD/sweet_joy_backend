<?php

namespace App\Policies;

class SiteConfigurationPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'configurations.sites';
    }
}
