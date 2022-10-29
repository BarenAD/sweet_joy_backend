<?php

namespace App\Http\Controllers;


use App\Http\Requests\Users\Roles\DestroyUserRoleRequest;
use App\Http\Requests\Users\Roles\IndexUserRoleRequest;
use App\Http\Requests\Users\Roles\StoreUserRoleRequest;
use App\Repositories\UserRoleRepository;

/**
 * Class AdminInformationController
 * @package App\Http\Controllers
 */
class UserRoleController extends Controller
{
    private UserRoleRepository $userRoleRepository;

    public function __construct(UserRoleRepository $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
    }

    public function index(IndexUserRoleRequest $request, int $userId)
    {
        return response($this->userRoleRepository->getUserRoles($userId), 200);
    }

    public function show(IndexUserRoleRequest $request, int $userId, int $id)
    {
        return response($this->userRoleRepository->findByUser($userId, $id), 200);
    }

    public function store(StoreUserRoleRequest $request, int $userId)
    {
        $params = $request->validated();
        $params['user_id'] = $userId;
        return response($this->userRoleRepository->store($params), 200);
    }

    public function destroy(DestroyUserRoleRequest $request, int $userId, int $id)
    {
        return response($this->userRoleRepository->destroyByUser($userId, $id), 200);
    }
}
