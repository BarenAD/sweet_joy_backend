<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateAdminInfo;
use App\Repositories\AdminInformationRepository;
use Illuminate\Http\Request;

class AdminInformationController extends Controller
{
    private $adminInformationRepository;

    public function __construct(AdminInformationRepository $adminInformationRepository)
    {
        $this->adminInformationRepository = $adminInformationRepository;
    }

    public function getAdmins(Request $request) {
        return response($this->adminInformationRepository->getAdmins($request->get('id_u')), 200);
    }

    public function createAdmin(ChangeOrCreateAdminInfo $request) {
        return response($this->adminInformationRepository->createAdmin(
            $request->user(),
            $request->get('id_u'),
            $request->get('ids_pos')
        ), 200);
    }

    public function changeAdmin(ChangeOrCreateAdminInfo $request) {
        return response($this->adminInformationRepository->changeAdmin($request->get('id_u'), $request->get('ids_pos')), 200);
    }

    public function deleteAdmin(Request $request) {
        return response($this->adminInformationRepository->deleteAdmin($request->get('id_u')), 200);
    }
}
