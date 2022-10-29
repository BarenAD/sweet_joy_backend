<?php


namespace App\Repositories;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    public function getUserRoles(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->get();
    }

    public function findByUser(int $userId, int $id): Model
    {
        return $this->model
            ->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function destroyByUser(int $userId, int $id): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('id', $id)
            ->delete();
    }
}
