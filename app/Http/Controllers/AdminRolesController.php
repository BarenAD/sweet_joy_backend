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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getRoles(Request $request)
    {
        return response($this->adminRolesService->getRoles($request->get('id')), 200);
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeRole(ChangeOrCreateAdminRole $request)
    {
        return response(
            $this->adminRolesService->changeRole(
                $request->user(),
                (int) $request->get('id'),
                $request->get('name'),
                $request->get('actions')
            ),
            200
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteRole(Request $request)
    {
        return response($this->adminRolesService->deleteRole($request->user(), (int) $request->get('id')), 200);
    }
}
