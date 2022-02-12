<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateAdminRole;
use App\Http\Services\AdminRolesService;
use Illuminate\Http\Request;

/**
 * Class AdminRolesController
 * @package App\Http\Controllers
 */
class AdminRolesController extends Controller
{
    private AdminRolesService $adminRolesService;

    public function __construct(AdminRolesService $adminRolesService)
    {
        $this->adminRolesService = $adminRolesService;
    }

    public function getRoles(Request $request, int $id = null)
    {
        return response($this->adminRolesService->getRoles($id), 200);
    }

    public function createRole(ChangeOrCreateAdminRole $request)
    {
        return response(
            $this->adminRolesService->createRole(
                $request->user(),
                $request->get('name'),
                $request->get('actions')
            ),
            200
        );
    }

    public function changeRole(ChangeOrCreateAdminRole $request, int $id)
    {
        return response(
            $this->adminRolesService->changeRole(
                $request->user(),
                $id,
                $request->get('name'),
                $request->get('actions')
            ),
            200
        );
    }

    public function deleteRole(Request $request, int $id)
    {
        return response($this->adminRolesService->deleteRole($request->user(), $id), 200);
    }
}
