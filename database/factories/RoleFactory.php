<?php

namespace Database\Factories;

use App\Models\Role;

class RoleFactory extends CoreFactory
{
    protected $model = Role::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'description' => $this->faker->text(100),
        ]);
    }
}
