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
    private $adminRolesRepository;
    private $adminGrantsRepository;

    /**
     * AdminRolesService constructor.
     * @param AdminRolesRepository $adminRolesRepository
     * @param AdminGrantsRepository $adminGrantsRepository
     */
    public function __construct(AdminRolesRepository $adminRolesRepository, AdminGrantsRepository $adminGrantsRepository)
    {
        $this->adminRolesRepository = $adminRolesRepository;
        $this->adminGrantsRepository = $adminGrantsRepository;
    }

    /**
     * @param $adminActions
     * @return array
     */
    private function extractIdsFromAdminActions($adminActions) {
        $resultArray = [];
        if (isset($adminActions)) {
            foreach ($adminActions as $action) {
                array_push($resultArray, $action->id_aa);
            }
        }
        return $resultArray;
    }

    /**
     * @return array
     */
    public function getRolesActions() {
        $adminRoles = $this->adminRolesRepository->getAdminRoles();
        $adminsActions = $this->adminGrantsRepository->getAdminGrants()->groupBy('id_ar');
        $resultArray = [];
        foreach ($adminRoles as $role) {
            $resultArray[$role->id] = $this->extractIdsFromAdminActions($adminsActions[$role->id]);
        }
        return $resultArray;
    }

    /**
     * @param int|null $id
     * @return array
     */
    public function getRoles(int $id = null) {
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

    /**
     * @param User $user
     * @param string $name
     * @param array $actions
     * @return mixed
     */
    public function createRole(User $user, string $name, array $actions) {
        if (RolesPolicy::canCreate($user)) {
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

    /**
     * @param User $user
     * @param int $id
     * @param string $name
     * @param array $actions
     * @return mixed
     */
    public function changeRole(User $user, int $id, string $name, array $actions) {
        if (RolesPolicy::canUpdate($user)) {
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

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function deleteRole(User $user, int $id) {
        if (RolesPolicy::canDelete($user)) {
            return $this->adminRolesRepository->getAdminRoles($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
