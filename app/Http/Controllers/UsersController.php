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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        return response($this->userService->getUsers($request->get('id')), 200);
    }

    /**
     * @param ChangeUser $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeUser(ChangeUser $request)
    {
        return response(
            $this->userService->changeUser(
                $request->user(),
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteUser(Request $request)
    {
        return response($this->userService->deleteUser($request->user(), $request->get('id')), 200);
    }
}
