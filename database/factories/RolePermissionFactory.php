<?php

namespace Database\Factories;

use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Model;

class RolePermissionFactory extends CoreFactory
{
    protected $model = RolePermission::class;

    public function definition()
    {
        return $this->decorateTimestamp();
    }
}
