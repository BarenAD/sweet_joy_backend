<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUser;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsers(Request $request) {
        return response($this->userRepository->getUsers($request->get('id')), 200);
    }

    public function changeUser(ChangeUser $request) {
        return response(
            $this->userRepository->changeUser(
                $request->get('id'),
                $request->get('fio'),
                $request->get('login'),
                $request->get('password'),
                $request->get('email'),
                $request->get('email_verified_at'),
                $request->get('phone'),
                $request->get('note')
            ),
            200
        );
    }

    public function deleteUser(Request $request) {
        return response($this->userRepository->deleteUser($request->get('id')), 200);
    }
}
