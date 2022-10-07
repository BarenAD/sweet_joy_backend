<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\Permissions\DestroyRolePermissionRequest;
use App\Http\Requests\Roles\Permissions\IndexRolePermissionRequest;
use App\Http\Requests\Roles\Permissions\StoreRolePermissionRequest;
use App\Repositories\RolePermissionRepository;

/**
 * Class AdminRolesController
 * @package App\Http\Controllers
 */
class RolePermissionController extends Controller
{
    private RolePermissionRepository $rolePermissionRepository;

    public function __construct(RolePermissionRepository $rolePermissionRepository)
    {
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    public function index(IndexRolePermissionRequest $request)
    {
        return response($this->rolePermissionRepository->getAll(), 200);
    }

    public function show(IndexRolePermissionRequest $request, int $id)
    {
        return response($this->rolePermissionRepository->find($id), 200);
    }

    public function store(StoreRolePermissionRequest $request)
    {
        return response($this->rolePermissionRepository->store($request->validated()), 200);
    }

    public function destroy(DestroyRolePermissionRequest $request, int $id)
    {
        return response($this->rolePermissionRepository->destroy($id), 200);
    }
}
