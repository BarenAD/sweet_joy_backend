<?php


namespace App\Repositories;


use App\Models\User;

class UserRepository
{
    public function getUsers(int $id = null) {
        if (isset($id)) {
            return User::findOrFail($id);
        }
        $users = User::all()->toArray();
        return $users;
    }

    public function changeUser(
        int $id,
        string $fio = null,
        string $login = null,
        string $password = null,
        string $email = null,
        string $email_verified_at = null,
        string $phone = null,
        string $note = null
    ) {
        $user = User::findOrFail($id);
        if (isset($fio)) {$user->fill(['fio' => $fio]);}
        if (isset($login)) {$user->fill(['login' => $login]);}
        if (isset($password)) {$user->fill(['password' => bcrypt($password)]);}
        if (isset($email)) {$user->fill(['email' => $email]);}
        if (isset($email_verified_at)) {$user->fill(['email_verified_at' => $email_verified_at]);}
        if (isset($phone)) {$user->fill(['phone' => $phone]);}
        if (isset($note)) {$user->fill(['note' => $note]);}
        $user->save();
        return $user;
    }

    public function deleteUser(int $id) {
        return User::findOrFail($id)->delete();
    }
}
