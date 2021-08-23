<?php


namespace App\Repositories;

use App\Models\AdminInformation;

/**
 * Class AdminInformationRepository
 * @package App\Repositories
 */
class AdminInformationRepository
{
    private $model;

    /**
     * AdminInformationRepository constructor.
     * @param AdminInformation $adminInformation
     */
    public function __construct(AdminInformation $adminInformation)
    {
        $this->model = $adminInformation;
    }

    /**
     * @param int|null $id_u
     * @return AdminInformation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAdminsInfo(int $id_u = null)
    {
        if (isset($id_u)) {
            return $this->model::where('id_u', $id_u)->get();
        }
        return $this->model::all();
    }

    /**
     * @param int $role
     * @param int $id_pos
     * @param int $id_u
     * @return mixed
     */
    public function createAdminInfo(int $role, int $id_pos, int $id_u)
    {
        return $this->model::create([
            'id_ar' => $role,
            'id_pos' => $id_pos,
            'id_u' => $id_u
        ]);
    }
}
