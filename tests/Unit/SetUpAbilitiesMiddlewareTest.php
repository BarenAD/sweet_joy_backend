<?php


namespace Tests\Unit;

use App\Http\Middleware\SetUpAbilities;
use App\Http\Utils\UserPermissionUtil;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SetUpAbilitiesMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private Model $user;
    private string $token;
    private Collection $permissions;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('Unit Test Client', [])->plainTextToken;
    }

    public function testHandle()
    {
        $this->permissions = Permission::factory()
            ->count(10)
            ->create();
        $role = Role::factory()->create();
        foreach ($this->permissions as $permission) {
            RolePermission::factory([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ])->create();
        }
        UserRole::factory([
            'user_id' => $this->user->id,
            'role_id' => $role->id,
        ])->create();
        $middleware = app()->make(SetUpAbilities::class);
        $request = app()->make(Request::class);

        $request->setUserResolver(function () {
            return $this->user;
        });

        $middleware->handle($request, function () {});

        $this->assertEquals(
            Session('user_permissions'),
            $this->permissions
                ->pluck('permission')
                ->toArray()
        );
    }

    public function testHandleNoRoles()
    {
        $middleware = app()->make(SetUpAbilities::class);
        $request = app()->make(Request::class);

        $request->setUserResolver(function () {
            return $this->user;
        });

        try {
            $middleware->handle($request, function () {});
            $this->markTestIncomplete();
        } catch (\Throwable $exception) {
            $this->assertTrue($exception->getMessage() === config('exceptions.is_not_admin.message'));
        }
    }
}
