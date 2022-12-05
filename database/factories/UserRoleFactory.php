<?php

namespace Database\Factories;

use App\Models\UserRole;

class UserRoleFactory extends CoreFactory
{
    protected $model = UserRole::class;

    public function definition()
    {
        return $this->decorateTimestamp();
    }
}
