<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permissions\IndexPermissionRequest;
use App\Repositories\PermissionRepository;

/**
 * Class AdminActionsController
 * @package App\Http\Controllers
 */
class PermissionController extends Controller
{
    private PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
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
