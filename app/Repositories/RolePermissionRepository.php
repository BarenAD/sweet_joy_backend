<?php


namespace App\Repositories;



use App\Models\RolePermission;

class RolePermissionRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return RolePermission::class;
    }
}
