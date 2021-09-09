<?php


namespace App\Repositories;


use App\Models\AdminGrant;

/**
 * Class AdminGrantsRepository
 * @package App\Repositories
 */
class AdminGrantsRepository
{
    private $model;

    /**
     * AdminGrantsRepository constructor.
     * @param AdminGrant $adminGrant
     */
    public function __construct(AdminGrant $adminGrant)
    {
        $this->model = $adminGrant;
    }

    /**
     * @return AdminGrant[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAdminGrants()
    {
        return $this->model::all();
    }

    /**
     * @param int $id_admin_role
     * @return mixed
     */
    public function getAdminGrantsOnRole(int $id_admin_role)
    {
        return $this->model::where('id_ar', $id_admin_role)->get();
    }

    /**
     * @param int $id_admin_role
     * @param int $id_admin_action
     * @return mixed
     */
    public function create(int $id_admin_role, int $id_admin_action)
    {
        return $this->model::create([
            'id_ar' => $id_admin_role,
            'id_aa' => $id_admin_action
        ]);
    }
}
