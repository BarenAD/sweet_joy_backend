<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateAdminInfo;
use App\Http\services\GeneratedAborting;
use App\Policies\AdminInformationPolicy;
use App\Repositories\AdminInformationRepository;
use Illuminate\Http\Request;

class AdminInformationController extends Controller
{
    public function getAdmins(Request $request) {
        $actions = AdminInformationPolicy::canViewAny($request->user());
        if (!isset($actions)) {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return response(AdminInformationRepository::getAdmins($request->get('id_u')), 200);
    }

    public function createAdmin(ChangeOrCreateAdminInfo $request) {
        return response(AdminInformationRepository::createAdmin(
            $request->user(),
            $request->get('id_u'),
            $request->get('ids_pos')
        ), 200);
    }

    public function changeAdmin(ChangeOrCreateAdminInfo $request) {
        return response(AdminInformationRepository::changeAdmin(
            $request->user(),
            $request->get('id_u'),
            $request->get('ids_pos')
        ), 200);
    }

    public function deleteAdmin(Request $request) {
        return response(AdminInformationRepository::deleteAdmin(
            $request->user(),
            $request->get('id_u')
        ), 200);
    }
}
