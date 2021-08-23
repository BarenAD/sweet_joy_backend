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
    private $adminInformationService;

    /**
     * AdminInformationController constructor.
     * @param AdminInformationService $adminInformationService
     */
    public function __construct(AdminInformationService $adminInformationService)
    {
        $this->adminInformationService = $adminInformationService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAdmins(Request $request) {
        $actions = AdminInformationPolicy::canViewAny($request->user());
        if (!isset($actions)) {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return response($this->adminInformationService->getAdmins($request->get('id_u')), 200);
    }

    /**
     * @param ChangeOrCreateAdminInfo $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createAdmin(ChangeOrCreateAdminInfo $request) {
        return response($this->adminInformationService->createAdmin(
            $request->user(),
            $request->get('id_u'),
            $request->get('ids_pos')
        ), 200);
    }

    /**
     * @param ChangeOrCreateAdminInfo $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeAdmin(ChangeOrCreateAdminInfo $request) {
        return response($this->adminInformationService->changeAdmin(
            $request->user(),
            $request->get('id_u'),
            $request->get('ids_pos')
        ), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteAdmin(Request $request) {
        return response($this->adminInformationService->deleteAdmin(
            $request->user(),
            $request->get('id_u')
        ), 200);
    }
}
