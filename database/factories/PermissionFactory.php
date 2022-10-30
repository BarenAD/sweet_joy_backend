<?php

namespace Database\Factories;

use App\Models\Permission;

class PermissionFactory extends CoreFactory
{
    protected $model = Permission::class;
    private int $availableLengthPartsPermission = 5;
    private array $availableLastPartPermissions = [
        '*',
        'index',
        'show',
        'store',
        'update',
        'destroy',
    ];

    private function generatePermission(string &$result = '', int &$iteration = 0)
    {
        if ($iteration === 0) {
            $result = uniqid('permission_');
        }
        if ($iteration < $this->availableLengthPartsPermission || rand(0, 4) === 2) {
            $iteration++;
            $result = $result . '.' . $this->faker->word();
            return $this->generatePermission($result, $iteration);
        }
        return  $result . '.' . $this->availableLastPartPermissions[rand(0, count($this->availableLastPartPermissions)-1)];
    }

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'description' => $this->faker->text(200),
            'permission' => $this->generatePermission(),
        ]);
    }
}
