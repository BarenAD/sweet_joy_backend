<?php

namespace App\Policies;

class CategoryPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'categories';
    }
}
