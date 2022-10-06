<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\UserRepository;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index(IndexUserRequest $request)
    {
        return response($this->userRepository->getAll(), 200);
    }

    public function show(IndexUserRequest $request, int $id)
    {
        return response($this->userRepository->find($id), 200);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $params = $request->validated();
        if (isset($params['password'])) {
            $params['password'] = bcrypt($params['password']);
        }
        return response($this->userRepository->update($id, $params), 200);
    }

    public function destroy(DeleteUserRequest $request, int $id)
    {
        return response($this->userRepository->destroy($id), 200);
    }
}
