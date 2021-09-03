<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateAdminRole;
use App\Http\Services\AdminRolesService;
use App\Repositories\AdminRolesRepository;
use Illuminate\Http\Request;

/**
 * Class AdminRolesController
 * @package App\Http\Controllers
 */
class AdminRolesController extends Controller
{
    private $adminRolesService;

    /**
     * AdminRolesController constructor.
     * @param AdminRolesService $adminRolesService
     */
    public function __construct(AdminRolesService $adminRolesService)
    {
        $this->adminRolesService = $adminRolesService;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getRoles(Request $request, int $id = null)
    {
        return response($this->adminRolesService->getRoles($id), 200);
    }

    /**
     * @param ChangeOrCreateAdminRole $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * @param ChangeOrCreateAdminRole $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteRole(Request $request, int $id)
    {
        return response($this->adminRolesService->deleteRole($request->user(), $id), 200);
    }
}
