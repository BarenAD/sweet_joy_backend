<?php

namespace App\Policies;

class RolePolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'roles';
    }
}
