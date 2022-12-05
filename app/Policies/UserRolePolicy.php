<?php

namespace App\Policies;

class UserRolePolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'users.roles';
    }
}
