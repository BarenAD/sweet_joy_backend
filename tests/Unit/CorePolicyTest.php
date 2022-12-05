<?php


namespace Tests\Unit;


use App\Policies\CorePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class TestCorePolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'test.test.test';
    }

    public function isNotPermissions()
    {
        return $this->authorize();
    }
}

class CorePolicyTest extends TestCase
{
    use RefreshDatabase;

    private TestCorePolicy $testCorePolicy;

    public function setUp(): void
    {
        parent::setUp();
        $this->testCorePolicy = app()->make(TestCorePolicy::class);
    }

    public function testAuthorizeIsNotPermissions()
    {
        $this->assertTrue($this->testCorePolicy->isNotPermissions());
    }

    public function testAuthorizeWithPermission()
    {
        Session::flash('user_permissions', ['test.test.*']);
        $this->assertTrue($this->testCorePolicy->canUpdate());
    }

    public function testAuthorizeWithNotPermission()
    {
        Session::flash('user_permissions', ['test.test.test.index']);
        try {
            $this->assertTrue($this->testCorePolicy->canUpdate());
        } catch (\Throwable $exception) {
            $this->assertTrue(
                $exception->getMessage() === config('exceptions.not_enough_permissions.message')
            );
        }
    }

    public function testAuthorizeWithEmptyPermission()
    {
        try {
            $this->assertTrue($this->testCorePolicy->canUpdate());
        } catch (\Throwable $exception) {
            $this->assertTrue(
                $exception->getMessage() === config('exceptions.not_enough_permissions.message')
            );
        }
    }
}
