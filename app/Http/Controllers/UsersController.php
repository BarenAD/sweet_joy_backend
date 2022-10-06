<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUser;
use App\Http\Services\UserService;
use Illuminate\Http\Request;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers(Request $request, int $id = null)
    {
        return response($this->userService->getUsers($id), 200);
    }

    public function changeUser(ChangeUser $request, int $id)
    {
        return response(
            $this->userService->changeUser(
                $request->user(),
                $id,
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

    public function deleteUser(Request $request, int $id)
    {
        return response($this->userService->deleteUser($request->user(), $id), 200);
    }
}
