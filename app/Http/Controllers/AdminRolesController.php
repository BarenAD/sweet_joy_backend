<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateAdminRole;
use App\Repositories\Interfaces\AdminRolesRepositoryInterface;
use Illuminate\Http\Request;

class AdminRolesController extends Controller
{
    private $adminRolesRepository;

    public function __construct(AdminRolesRepositoryInterface $adminRolesRepository)
    {
        $this->adminRolesRepository = $adminRolesRepository;
    }

    public function getRoles(Request $request) {
        return response($this->adminRolesRepository->getRoles($request->get('id')), 200);
    }

    public function createRole(ChangeOrCreateAdminRole $request) {
        return response(
            $this->adminRolesRepository->createRole(
                $request->get('name'),
                $request->get('actions')
            ),
            200
        );
    }

    public function changeRole(ChangeOrCreateAdminRole $request) {
        return response(
            $this->adminRolesRepository->changeRole(
                (int) $request->get('id'),
                $request->get('name'),
                $request->get('actions')
            ),
            200
        );
    }

    public function deleteRole(Request $request) {
        return response($this->adminRolesRepository->deleteRole((int) $request->get('id')), 200);
    }
}
