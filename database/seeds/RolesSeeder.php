<?php

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    private array $inserting = [
        [
            'role' => [
                'id' => 1,
                'name' => 'Супер менеджер пользователей',
                'description' => 'Любые действия над пользователями и их ролями.',
            ],
            'permissionsIds' => [12],
        ],
        [
            'role' => [
                'id' => 2,
                'name' => 'Менеджер пользователей',
                'description' => 'Просматривать, создавать, изменять, удалять пользователей.',
            ],
            'permissionsIds' => [13,14,15,16],
        ],
        [
            'role' => [
                'id' => 3,
                'name' => 'Менеджер ролей пользователей',
                'description' => 'Просматривать, добавлять, удалять роли пользователям. (управление администраторами)',
            ],
            'permissionsIds' => [18,19,20],
        ],
        [
            'role' => [
                'id' => 4,
                'name' => 'Супер менеджер ролей',
                'description' => 'Любые действия над ролями, а также их правами.',
            ],
            'permissionsIds' => [3],
        ],
        [
            'role' => [
                'id' => 5,
                'name' => 'Менеджер ролей',
                'description' => 'Просматривать, добавлять, изменять, удалять роли.',
            ],
            'permissionsIds' => [4,5,6,7],
        ],
        [
            'role' => [
                'id' => 6,
                'name' => 'Менеджер прав ролей',
                'description' => 'Просматривать, добавлять, удалять права ролям.',
            ],
            'permissionsIds' => [9,10,11],
        ],
        [
            'role' => [
                'id' => 7,
                'name' => 'Менеджер категорий',
                'description' => 'Просматривать, добавлять, изменять, удалять категории.',
            ],
            'permissionsIds' => [22,23,24,25],
        ],
        [
            'role' => [
                'id' => 8,
                'name' => 'Менеджер продуктов (товаров)',
                'description' => 'Просматривать, добавлять, изменять, удалять товары. Просматривать категории',
            ],
            'permissionsIds' => [22,27,28,29,30],
        ],
        [
            'role' => [
                'id' => 9,
                'name' => 'Менеджер расписаний',
                'description' => 'Просматривать, добавлять, изменять, удалять расписания.',
            ],
            'permissionsIds' => [32,33,34,35],
        ],
        [
            'role' => [
                'id' => 10,
                'name' => 'Менеджер торговых точек',
                'description' => 'Просматривать, добавлять, изменять, удалять торговые точки. Просматривать расписания',
            ],
            'permissionsIds' => [32,37,38,39,40],
        ],
        [
            'role' => [
                'id' => 11,
                'name' => 'Менеджер товаров в торговых точках',
                'description' => 'Просматривать, добавлять, изменять, удалять товары в торговых точках. Просматривать: категории, категории на продуктах, торговые точки, товары, товары в торговых точках.',
            ],
            'permissionsIds' => [22,27,32,42,43,44,45,58],
        ],
        [
            'role' => [
                'id' => 12,
                'name' => 'Менеджер конфигурации сайта',
                'description' => 'Просматривать и редактировать конфигурационные данные сайта',
            ],
            'permissionsIds' => [47,48],
        ],
        [
            'role' => [
                'id' => 13,
                'name' => 'Супер менеджер документов сайта',
                'description' => 'Просматривать, добавлять, изменять, удалять докуметы. Менять их расположение.',
            ],
            'permissionsIds' => [49],
        ],
        [
            'role' => [
                'id' => 14,
                'name' => 'Менеджер документов сайта',
                'description' => 'Просматривать, добавлять, изменять, удалять докуметы.',
            ],
            'permissionsIds' => [50,51,52,53],
        ],
        [
            'role' => [
                'id' => 15,
                'name' => 'Менеджер локаций документов сайта',
                'description' => 'Просматривать, изменять локации документов. Просматривать документы.',
            ],
            'permissionsIds' => [50,55,56],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->inserting as $params) {
            $existRoleModel = Role::query()->find($params['role']['id']);
            if ($existRoleModel) {
                $existRoleModel->update($params['role']);
            } else {
                $existRoleModel = new Role($params['role']);
                $existRoleModel->save();
            }
            $permissionsRole = RolePermission::query()
                ->where('role_id', $params['role']['id'])
                ->whereIn('permission_id', $params['permissionsIds'])
                ->get();
            $processedPermissions = $params['permissionsIds'];
            foreach ($permissionsRole as $permissionsRole) {
                $index = array_search($permissionsRole->permission_id, $processedPermissions);
                if ($index) {
                    unset($processedPermissions[$index]);
                } else {
                    $permissionsRole->delete();
                }
            }
            foreach ($processedPermissions as $permissionId) {
                RolePermission::query()->create([
                    'role_id' => $existRoleModel->id,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }
}
