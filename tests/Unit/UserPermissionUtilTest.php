<?php


namespace Tests\Unit;


use App\Http\Utils\UserPermissionUtil;
use App\Models\Operator;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPermissionUtilTest extends TestCase
{
    use RefreshDatabase;

    private Model $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testGetPermissionOperator()
    {
        $userPermissionUtil = app()->make(UserPermissionUtil::class);
        $operator = new Operator(['user_id' => $this->user->id]);
        $operator->save();
        $result = $userPermissionUtil->getUserPermissions($this->user->id);
        $this->assertEquals($result, ['*']);
    }

    public function testGetPermissionNoRoles()
    {
        $userPermissionUtil = app()->make(UserPermissionUtil::class);
        $this->assertEquals($userPermissionUtil->getUserPermissions($this->user->id), []);
    }

    public function testGetPermissionWithRoles()
    {
        $userPermissionUtil = app()->make(UserPermissionUtil::class);
        $permissions = Permission::factory()
            ->count(10)
            ->create();
        $role = Role::factory()->create();
        foreach ($permissions as $permission) {
            RolePermission::factory([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ])->create();
        }
        UserRole::factory([
            'user_id' => $this->user->id,
            'role_id' => $role->id,
        ])->create();
        $result = $userPermissionUtil->getUserPermissions($this->user->id);
        $this->assertEquals($result, $permissions->pluck('permission')->toArray());
    }

    public function testCheckCanActionByPermissionsOperator()
    {
        $userPermissionUtil = app()->make(UserPermissionUtil::class);
        $result = $userPermissionUtil->checkCanActionByPermissions(
            ['users.roles.index', 'users.roles.update', 'users.roles.destroy'],
            ['*']
        );
        $this->assertTrue($result);
    }

    public function testCheckCanActionByPermissionsExist()
    {
        $userPermissionUtil = app()->make(UserPermissionUtil::class);
        $result = $userPermissionUtil->checkCanActionByPermissions(
            ['users.roles.index', 'users.roles.update', 'users.roles.destroy'],
            ['users.roles.update']
        );
        $this->assertTrue($result);
    }

    public function testCheckCanActionByPermissionsNotObviousExist()
    {
        $userPermissionUtil = app()->make(UserPermissionUtil::class);
        $result = $userPermissionUtil->checkCanActionByPermissions(
            ['users.roles.index', 'users.roles.update', 'users.roles.destroy'],
            ['users.*']
        );
        $this->assertTrue($result);
    }

    public function testCheckCanActionByPermissionsNotExist()
    {
        $userPermissionUtil = app()->make(UserPermissionUtil::class);
        $result = $userPermissionUtil->checkCanActionByPermissions(
            ['users.roles.index', 'users.roles.update', 'users.roles.destroy'],
            ['users.index']
        );
        $this->assertFalse($result);
    }

}
