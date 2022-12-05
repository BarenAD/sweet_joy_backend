<?php


namespace Tests\Traits;


use App\Http\Utils\UserPermissionUtil;
use Mockery\MockInterface;

trait WithoutPermissionsTrait
{
    public function withoutPermissionSetUp()
    {
        $this->mock(
            UserPermissionUtil::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('checkCanActionByPermissions')->andReturn(true);
            }
        );
    }
}
