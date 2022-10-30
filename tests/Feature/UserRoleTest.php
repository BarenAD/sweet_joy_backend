<?php


namespace Tests\Feature;


use App\DTO\ParentModelDTO;
use App\Http\Requests\Users\Roles\StoreUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Tests\TestApiResource;

class UserRoleTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.users.roles';
        $this->model = new UserRole();
        $this->except = ['update'];
        $this->formRequests = [
            'store' => StoreUserRoleRequest::class,
        ];
        $this->parentModelDTOs = [
            ParentModelDTO::make([
                'model' => new User(),
                'foreignKey' => 'user_id',
                'needInRoute' => true,
            ]),
            ParentModelDTO::make([
                'model' => new Role(),
                'foreignKey' => 'role_id',
            ]),
        ];
    }
}
