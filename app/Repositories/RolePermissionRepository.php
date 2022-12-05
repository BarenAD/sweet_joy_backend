<?php


namespace App\Repositories;



use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RolePermissionRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return RolePermission::class;
    }

    public function getRolePermissions(int $roleId): Collection
    {
        return $this->model
            ->where('role_id', $roleId)
            ->get();
    }

    public function findByRole(int $roleId, int $id): Model
    {
        return $this->model
            ->where('role_id', $roleId)
            ->findOrFail($id);
    }

    public function destroyByRole(int $roleId, int $id): int
    {
        return $this->model
            ->where('role_id', $roleId)
            ->where('id', $id)
            ->delete();
    }
}
