<?php

namespace App\Policies;

class ProductPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'products';
    }
}

