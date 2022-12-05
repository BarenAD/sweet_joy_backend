<?php

namespace App\Policies;

class PermissionPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'permissions';
    }
}
