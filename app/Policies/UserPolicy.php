<?php

namespace App\Policies;

class UserPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'users';
    }
}
