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

    public function index(IndexUserRoleRequest $request)
    {
        return response($this->userRoleRepository->getAll(), 200);
    }

    public function show(IndexUserRoleRequest $request, int $id)
    {
        return response($this->userRoleRepository->find($id), 200);
    }

    public function store(StoreUserRoleRequest $request)
    {
        return response($this->userRoleRepository->store($request->validated()), 200);
    }

    public function destroy(DestroyUserRoleRequest $request, int $id)
    {
        return response($this->userRoleRepository->destroy($id), 200);
    }
}
