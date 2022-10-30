<?php


namespace Tests\Feature;


use App\DTO\ParentModelDTO;
use App\Http\Requests\Roles\Permissions\StoreRolePermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Tests\TestApiResource;

class RolePermissionTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.roles.permissions';
        $this->model = new RolePermission();
        $this->except = ['update'];
        $this->formRequests = [
            'store' => StoreRolePermissionRequest::class,
        ];
        $this->parentModelDTOs = [
            ParentModelDTO::make([
                'model' => new Role(),
                'foreignKey' => 'role_id',
                'needInRoute' => true,
            ]),
            ParentModelDTO::make([
                'model' => new Permission(),
                'foreignKey' => 'permission_id',

            ]),
        ];
    }

    protected function seedsBD(): array
    {
        $permissionModel = $this->parentModelDTOs[1]->model;
        $result = [];
        for ($i = 0; $i < 10; $i++){
            $result[] = $this->model
                ->factory([
                    'role_id' => $this->parentModelsIds['role_id'],
                    'permission_id' => $permissionModel->factory()->create()['id'],
                ])
                ->create()
                ->toArray();
        }
        return $result;
    }
}
