<?php


namespace App\Repositories;


use App\Models\AdminGrant;

/**
 * Class AdminGrantsRepository
 * @package App\Repositories
 */
class AdminGrantsRepository
{
    private AdminGrant $model;

    public function __construct(AdminGrant $adminGrant)
    {
        $this->model = $adminGrant;
    }

    public function getAdminGrants()
    {
        return $this->model::all();
    }

    public function getAdminGrantsOnRole(int $id_admin_role)
    {
        return $this->model::where('id_ar', $id_admin_role)->get();
    }

    public function create(int $id_admin_role, int $id_admin_action)
    {
        return $this->model::create([
            'id_ar' => $id_admin_role,
            'id_aa' => $id_admin_action
        ]);
    }
}
