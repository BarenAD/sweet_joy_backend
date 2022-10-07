<?php


namespace App\Repositories;


use App\Models\Permission;

class PermissionRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Permission::class;
    }
}
