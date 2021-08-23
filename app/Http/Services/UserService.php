<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 15:52
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\UsersPolicy;
use App\Repositories\UserRepository;

/**
 * Class UserService
 * @package App\Http\Services
 */
class UserService
{
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int|null $id
     * @return UserRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUsers(int $id = null)
    {
        return $this->userRepository->getUsers($id);
    }

    /**
     * @param User $user
     * @param int $id
     * @param string|null $fio
     * @param string|null $login
     * @param string|null $password
     * @param string|null $email
     * @param string|null $email_verified_at
     * @param string|null $phone
     * @param string|null $note
     * @return User|UserRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function changeUser(
        User $user,
        int $id,
        string $fio = null,
        string $login = null,
        string $password = null,
        string $email = null,
        string $email_verified_at = null,
        string $phone = null,
        string $note = null
    ) {
        if (UsersPolicy::canUpdate($user)) {
            $user = $this->userRepository->getUsers($id);
            if (isset($fio)) {
                $user->fill(['fio' => $fio]);
            }
            if (isset($login)) {
                $user->fill(['login' => $login]);
            }
            if (isset($password)) {
                $user->fill(['password' => bcrypt($password)]);
            }
            if (isset($email)) {
                $user->fill(['email' => $email]);
            }
            if (isset($email_verified_at)) {
                $user->fill(['email_verified_at' => $email_verified_at]);
            }
            if (isset($phone)) {
                $user->fill(['phone' => $phone]);
            }
            if (isset($note)) {
                $user->fill(['note' => $note]);
            }
            $user->save();
            return $user;
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function deleteUser(User $user, int $id) {
        if (UsersPolicy::canDelete($user)) {
            return $this->userRepository->getUsers($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
