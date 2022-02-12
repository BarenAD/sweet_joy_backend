<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 14:47
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\RolesPolicy;
use App\Repositories\AdminGrantsRepository;
use App\Repositories\AdminRolesRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminRolesService
 * @package App\Http\Services
 */
class AdminRolesService
{
    private AdminRolesRepository $adminRolesRepository;
    private AdminGrantsRepository $adminGrantsRepository;
    private RolesPolicy $rolesPolicy;

    public function __construct(
        AdminRolesRepository $adminRolesRepository,
        AdminGrantsRepository $adminGrantsRepository,
        RolesPolicy $rolesPolicy
    ){
        $this->adminRolesRepository = $adminRolesRepository;
        $this->adminGrantsRepository = $adminGrantsRepository;
        $this->rolesPolicy = $rolesPolicy;
    }

    private function extractIdsFromAdminActions($adminActions)
    {
        $resultArray = [];
        if (isset($adminActions)) {
            foreach ($adminActions as $action) {
                array_push($resultArray, $action->id_aa);
            }
        }
        return $resultArray;
    }

    public function getRolesActions()
    {
        $adminRoles = $this->adminRolesRepository->getAdminRoles();
        $adminsActions = $this->adminGrantsRepository->getAdminGrants()->groupBy('id_ar');
        $resultArray = [];
        foreach ($adminRoles as $role) {
            $resultArray[$role->id] = $this->extractIdsFromAdminActions($adminsActions[$role->id]);
        }
        return $resultArray;
    }

    public function getRoles(int $id = null)
    {
        if (isset($id)) {
            $role = $this->adminRolesRepository->getAdminRoles($id);
            $adminActions = $this->adminGrantsRepository->getAdminGrantsOnRole($id);
            return [
                'role' => $role,
                'actions' => $this->extractIdsFromAdminActions($adminActions)
            ];
        }
        $adminRoles = $this->adminRolesRepository->getAdminRoles();
        $adminsActions = $this->adminGrantsRepository->getAdminGrants()->groupBy('id_ar');
        $resultArray = [];
        foreach ($adminRoles as $role) {
            array_push($resultArray, [
                'role' => $role,
                'actions' => $this->extractIdsFromAdminActions($adminsActions[$role->id])
            ]);
        }
        return $resultArray;
    }

    public function createRole(User $user, string $name, array $actions)
    {
        if ($this->rolesPolicy->canCreate($user)) {
            return DB::transaction(function () use ($name, $actions) {
                $resultRole = $this->adminRolesRepository->create($name);
                $resultActions = [];
                foreach ($actions as $action) {
                    array_push($resultActions, $this->adminGrantsRepository->create($resultRole->id, $action)->id_aa);
                }
                DB::commit();
                return [
                    'role' => $resultRole,
                    'actions' => $resultActions
                ];
            });
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function changeRole(User $user, int $id, string $name, array $actions)
    {
        if ($this->rolesPolicy->canUpdate($user)) {
            return DB::transaction(function () use ($id, $name, $actions) {
                $adminRole = $this->adminRolesRepository->getAdminRoles($id);
                $adminRole->fill(['name' => $name])->save();
                $adminActions = $this->adminGrantsRepository->getAdminGrantsOnRole($id);
                $resultActions = [];
                foreach ($adminActions as $adminAction) {
                    $key = array_search($adminAction->id_aa, $actions);
                    if ($key === false) {
                        $adminAction->delete();
                    } else {
                        unset($actions[$key]);
                        array_push($resultActions, $adminAction->id_aa);
                    }
                }
                foreach ($actions as $action) {
                    array_push($resultActions, $this->adminGrantsRepository->create($adminRole->id, $action)->id_aa);
                }
                DB::commit();
                return [
                    'role' => $adminRole,
                    'actions' => $resultActions
                ];
            });
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function deleteRole(User $user, int $id)
    {
        if ($this->rolesPolicy->canDelete($user)) {
            return $this->adminRolesRepository->getAdminRoles($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
