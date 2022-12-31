<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\DestroyUserRequest;
use App\Http\Requests\Users\IndexUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
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
        return response($this->userRepository->getAll()->makeVisible('note'), 200);
    }

    public function show(IndexUserRequest $request, int $id)
    {
        return response($this->userRepository->find($id)->makeVisible('note'), 200);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $params = $request->validated();
        if (isset($params['password'])) {
            $params['password'] = bcrypt($params['password']);
        }
        return response($this->userRepository->update($id, $params)->makeVisible('note'), 200);
    }

    public function destroy(DestroyUserRequest $request, int $id)
    {
        return response($this->userRepository->destroy($id), 200);
    }
}
