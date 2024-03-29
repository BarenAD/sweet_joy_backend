<?php


namespace App\Repositories;


use App\Models\User;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return User::class;
    }
}
