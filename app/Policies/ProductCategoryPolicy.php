<?php

namespace App\Policies;

class ProductCategoryPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'products.categories';
    }
}

