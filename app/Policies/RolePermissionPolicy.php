<?php

namespace App\Policies;

class RolePermissionPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'roles.permissions';
    }
}
