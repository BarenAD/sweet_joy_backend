<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUser;
use App\Http\Services\UserService;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    private $userService;

    /**
     * UsersController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getUsers(Request $request, int $id = null)
    {
        return response($this->userService->getUsers($id), 200);
    }

    /**
     * @param ChangeUser $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteUser(Request $request, int $id)
    {
        return response($this->userService->deleteUser($request->user(), $id), 200);
    }
}
