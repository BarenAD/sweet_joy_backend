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

    public function index(IndexRolePermissionRequest $request, int $roleId)
    {
        return response($this->rolePermissionRepository->getRolePermissions($roleId), 200);
    }

    public function show(IndexRolePermissionRequest $request, int $roleId, int $id)
    {
        return response($this->rolePermissionRepository->findByRole($roleId, $id), 200);
    }

    public function store(StoreRolePermissionRequest $request, int $roleId)
    {
        $params = $request->validated();
        $params['role_id'] = $roleId;
        return response($this->rolePermissionRepository->store($params), 200);
    }

    public function destroy(DestroyRolePermissionRequest $request, int $roleId, int $id)
    {
        return response($this->rolePermissionRepository->destroyByRole($roleId, $id), 200);
    }
}
