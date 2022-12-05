<?php

namespace App\Policies;

class ShopPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'shops';
    }
}
