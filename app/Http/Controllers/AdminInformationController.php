<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateAdminInfo;
use App\Http\Services\AdminInformationService;
use App\Http\Services\GeneratedAborting;
use App\Policies\AdminInformationPolicy;
use Illuminate\Http\Request;

/**
 * Class AdminInformationController
 * @package App\Http\Controllers
 */
class AdminInformationController extends Controller
{
    private AdminInformationService $adminInformationService;
    private AdminInformationPolicy $adminInformationPolicy;

    public function __construct(
        AdminInformationService $adminInformationService,
        AdminInformationPolicy $adminInformationPolicy
    ) {
        $this->adminInformationService = $adminInformationService;
        $this->adminInformationPolicy = $adminInformationPolicy;
    }

    public function getAdmins(Request $request, int $id_user)
    {
        $actions = $this->adminInformationPolicy->canViewAny($request->user());
        if (!isset($actions)) {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return response($this->adminInformationService->getAdmins($id_user), 200);
    }

    public function createAdmin(ChangeOrCreateAdminInfo $request)
    {
        return response($this->adminInformationService->createAdmin(
            $request->user(),
            $request->get('id_u'),
            $request->get('ids_pos')
        ), 200);
    }

    public function changeAdmin(ChangeOrCreateAdminInfo $request, int $id_user)
    {
        return response($this->adminInformationService->changeAdmin(
            $request->user(),
            $id_user,
            $request->get('ids_pos')
        ), 200);
    }

    public function deleteAdmin(Request $request, int $id_user)
    {
        return response($this->adminInformationService->deleteAdmin(
            $request->user(),
            $id_user
        ), 200);
    }
}
