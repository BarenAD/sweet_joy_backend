<?php

namespace App\Policies;

class ShopProductPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'shops.products';
    }
}
