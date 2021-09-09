<?php


namespace App\Repositories;


use App\Models\User;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository
{
    private $model;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param int|null $id
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUsers(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }
}
