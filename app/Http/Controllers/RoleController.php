<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\DestroyRoleRequest;
use App\Http\Requests\Roles\IndexRoleRequest;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Repositories\RoleRepository;

/**
 * Class AdminRolesController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(IndexRoleRequest $request)
    {
        return response($this->roleRepository->getAll(), 200);
    }

    public function show(IndexRoleRequest $request, int $id)
    {
        return response($this->roleRepository->find($id), 200);
    }

    public function store(StoreRoleRequest $request)
    {
        return response($this->roleRepository->store($request->validated()), 200);
    }

    public function update(UpdateRoleRequest $request, int $id)
    {
        return response($this->roleRepository->update($id, $request->validated()), 200);
    }

    public function destroy(DestroyRoleRequest $request, int $id)
    {
        return response($this->roleRepository->destroy($id), 200);
    }
}
