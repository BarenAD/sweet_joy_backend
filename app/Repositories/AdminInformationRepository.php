<?php


namespace App\Repositories;

use App\Models\AdminInformation;

/**
 * Class AdminInformationRepository
 * @package App\Repositories
 */
class AdminInformationRepository
{
    private AdminInformation $model;

    public function __construct(AdminInformation $adminInformation)
    {
        $this->model = $adminInformation;
    }

    public function getAdminsInfo(int $id_u = null)
    {
        if (isset($id_u)) {
            return $this->model::where('id_u', $id_u)->get();
        }
        return $this->model::all();
    }

    public function createAdminInfo(int $role, int $id_pos, int $id_u)
    {
        return $this->model::create([
            'id_ar' => $role,
            'id_pos' => $id_pos,
            'id_u' => $id_u
        ]);
    }
}
