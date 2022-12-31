<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permissions\IndexPermissionRequest;
use App\Http\Utils\UserPermissionUtil;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;

/**
 * Class AdminActionsController
 * @package App\Http\Controllers
 */
class PermissionController extends Controller
{
    private PermissionRepository $permissionRepository;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        PermissionRepository $permissionRepository,
        UserPermissionUtil $userPermissionUtil
    ){
        $this->permissionRepository = $permissionRepository;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function profilePermissions(Request $request)
    {
        $result = [];
        try {
            $result = $this->userPermissionUtil->getUserPermissions($request->user()->id);
        } catch (\Throwable $exception) {}
        return response($result, 200);
    }

    public function index(IndexPermissionRequest $request)
    {
        return response($this->permissionRepository->getAll(), 200);
    }

    public function show(IndexPermissionRequest $request, $id)
    {
        return response($this->permissionRepository->find($id), 200);
    }
}
