<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    private array $inserting = [
        [
            'id' => 1,
            'name' => 'Все права на редактирование прав',
            'description' => 'Позволяет управлять правами, которые присваиваются пользователям.',
            'permission' => 'permissions.*',
        ],
        [
            'id' => 2,
            'name' => 'Право на просмотр прав',
            'description' => 'Позволяет просматривать права, которые присваиваются пользователям.',
            'permission' => 'permissions.index',
        ],
        [
            'id' => 3,
            'name' => 'Право на добавление прав',
            'description' => 'Позволяет добавлять права, которые присваиваются пользователям.',
            'permission' => 'permissions.store',
        ],
        [
            'id' => 4,
            'name' => 'Право на обновление прав',
            'description' => 'Позволяет обновлять права, которые присваиваются пользователям.',
            'permission' => 'permissions.update',
        ],
        [
            'id' => 5,
            'name' => 'Право на удаление прав',
            'description' => 'Позволяет удалять права, которые присваиваются пользователям.',
            'permission' => 'permissions.destroy',
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
            $newModel = new Permission($params);
            if (Permission::query()->find($newModel->id)) {
                $newModel->update($params);
            } else {
                $newModel->save();
            }
        }
    }
}
