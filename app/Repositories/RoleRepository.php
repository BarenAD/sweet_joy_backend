<?php


namespace App\Repositories;


use App\Models\Role;

/**
 * Class AdminRolesRepository
 * @package App\Repositories
 */
class RoleRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Role::class;
    }
}
