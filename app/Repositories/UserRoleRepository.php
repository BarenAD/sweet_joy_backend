<?php


namespace App\Repositories;

use App\Models\UserRole;

/**
 * Class AdminInformationRepository
 * @package App\Repositories
 */
class UserRoleRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return UserRole::class;
    }
}
