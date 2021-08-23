<?php


namespace App\Repositories;


use App\Http\Services\GeneratedAborting;
use App\Models\AdminGrant;
use App\Models\AdminRole;
use App\Models\User;
use App\Policies\RolesPolicy;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminRolesRepository
 * @package App\Repositories
 */
class AdminRolesRepository
{
    private $model;

    /**
     * AdminRolesRepository constructor.
     * @param AdminRole $adminRole
     */
    public function __construct(AdminRole $adminRole)
    {
        $this->model = $adminRole;
    }

    /**
     * @param int|null $id
     * @return AdminRole[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAdminRoles(int $id = null)
    {
        if ($id) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function create($name)
    {
        return $this->model::create(['name' => $name]);
    }
}
