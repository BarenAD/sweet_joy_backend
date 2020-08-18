<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateAdminRole;
use App\Repositories\AdminRolesRepository;
use Illuminate\Http\Request;

class AdminRolesController extends Controller
{
    public function getRoles(Request $request) {
        return response(AdminRolesRepository::getRoles($request->get('id')), 200);
    }

    public function createRole(ChangeOrCreateAdminRole $request) {
        return response(
            AdminRolesRepository::createRole(
                $request->user(),
                $request->get('name'),
                $request->get('actions')
            ),
            200
        );
    }

    public function changeRole(ChangeOrCreateAdminRole $request) {
        return response(
            AdminRolesRepository::changeRole(
                $request->user(),
                (int) $request->get('id'),
                $request->get('name'),
                $request->get('actions')
            ),
            200
        );
    }

    public function deleteRole(Request $request) {
        return response(AdminRolesRepository::deleteRole($request->user(), (int) $request->get('id')), 200);
    }
}
