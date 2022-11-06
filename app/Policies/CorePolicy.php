<?php


namespace App\Policies;


use App\Exceptions\NoReportException;
use App\Http\Utils\UserPermissionUtil;

abstract class CorePolicy
{
    protected string $baseRule;
    private UserPermissionUtil $userPermissionsUtil;

    abstract protected function setUpParams(): void;

    public function __construct(UserPermissionUtil $userPermissionsUtil)
    {
        $this->userPermissionsUtil = $userPermissionsUtil;
        $this->setUpParams();
    }

    protected function authorize(array $permissions = [])
    {
        if (count($permissions) === 0) {
            return true;
        }
        if (!$this->userPermissionsUtil->checkCanActionByPermissions(
            $permissions,
            Session('user_permissions') ?? []
        )) {
            throw new NoReportException('not_enough_permissions');
        }
        return true;
    }

    public function canIndex(): bool
    {
        return $this->authorize([
            $this->baseRule . '.index',
            $this->baseRule . '.store',
            $this->baseRule . '.update',
            $this->baseRule . '.destroy',
        ]);
    }

    public function canStore(): bool
    {
        return $this->authorize([
            $this->baseRule . '.store',
        ]);
    }

    public function canUpdate(): bool
    {
        return $this->authorize([
            $this->baseRule . '.update',
        ]);
    }

    public function canDestroy(): bool
    {
        return $this->authorize([
            $this->baseRule . '.destroy',
        ]);
    }
}
