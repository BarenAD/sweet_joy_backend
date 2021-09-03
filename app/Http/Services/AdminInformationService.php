<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 11:03
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\AdminInformationPolicy;
use App\Repositories\AdminInformationRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminInformationService
 * @package App\Http\Services
 */
class AdminInformationService
{
    private $adminInformationRepository;

    /**
     * AdminInformationService constructor.
     * @param AdminInformationRepository $adminInformationRepository
     */
    public function __construct(AdminInformationRepository $adminInformationRepository)
    {
        $this->adminInformationRepository = $adminInformationRepository;
    }

    /**
     * @param int|null $id_u
     * @return \App\Models\AdminInformation[]|array|\Illuminate\Database\Eloquent\Collection
     */
    public function getAdmins(int $id_u = null)
    {
        if (isset($id_u)) {
            $admins = $this->adminInformationRepository->getAdminsInfo($id_u)->groupBy('id_pos');
            if (count($admins) === 0) {
                GeneratedAborting::notFound();
            }
            return $admins;
        } else {
            $admins = $this->adminInformationRepository->getAdminsInfo()->toArray();
        }
        $resultAdmins = [];
        foreach ($admins as $admin) {
            if (!isset($resultAdmins[$admin['id_u']])) {
                $resultAdmins[$admin['id_u']] = [];
            }
            if (!isset($resultAdmins[$admin['id_u']][$admin['id_pos']])) {
                $resultAdmins[$admin['id_u']][$admin['id_pos']] = [];
            }
            array_push($resultAdmins[$admin['id_u']][$admin['id_pos']],$admin);
        }
        return $resultAdmins;
    }

    /**
     * @param User $user
     * @param int $id_u
     * @param array $ids_pos
     * @return mixed
     */
    public function createAdmin(
        User $user,
        int $id_u,
        array $ids_pos
    ) {
        $admins = $this->adminInformationRepository->getAdminsInfo($id_u);
        if (count($admins) > 0) {
            GeneratedAborting::adminAlreadyExist();
        }
        return DB::transaction(function () use ($user, $id_u, $ids_pos) {
            $result = [];
            foreach ($ids_pos as $id_pos => $roles) {
                if (!isset($result[$id_pos])) {
                    $result[$id_pos] = [];
                }
                foreach ($roles as $role) {
                    if (AdminInformationPolicy::canCreate($user, $id_pos)) {
                        array_push($result[$id_pos], $this->adminInformationRepository->createAdminInfo($role, $id_pos, $id_u));
                    }
                }
                if (count($result[$id_pos]) === 0) {
                    unset($result[$id_pos]);
                }
            }
            DB::commit();
            if (count($result) === 0) {
                GeneratedAborting::accessDeniedGrandsAdmin();
            }
            return $result;
        });
    }

    /**
     * @param User $user
     * @param int $id_u
     * @param array $ids_pos
     * @return mixed
     */
    public function changeAdmin(
        User $user,
        int $id_u,
        array $ids_pos
    ) {
        return DB::transaction(function () use ($user, $id_u, $ids_pos) {
            $admins = $this->getAdmins($id_u);
            $result = [];
            foreach ($admins as $id_pos => $admin_roles) {
                $keyPos = in_array($id_pos, array_keys($ids_pos));
                foreach ($admin_roles as $admin_role) {
                    if ($keyPos === false) {
                        if (AdminInformationPolicy::canDelete($user, $admin_role)) {
                            $admin_role->delete();
                        }
                    } else {
                        $keyAr = array_search($admin_role->id_ar, $ids_pos[$id_pos]);
                        if ($keyAr === false) {
                            if (AdminInformationPolicy::canDelete($user, $admin_role)) {
                                $admin_role->delete();
                            }
                        } else {
                            unset($ids_pos[$id_pos][$keyAr]);
                            if (!isset($result[$id_pos])) {
                                $result[$id_pos] = [];
                            }
                            array_push($result[$id_pos], $admin_role);
                        }
                    }
                }
            }
            foreach ($ids_pos as $id_pos => $ids_admin_roles) {
                foreach ($ids_admin_roles as $id_ar) {
                    if (!isset($result[$id_pos])) {
                        $result[$id_pos] = [];
                    }
                    if (AdminInformationPolicy::canCreate($user, $id_pos)) {
                        array_push($result[$id_pos], $this->adminInformationRepository->createAdminInfo($id_ar, $id_pos, $id_u));
                    }
                    if (count($result[$id_pos]) === 0) {
                        unset($result[$id_pos]);
                    }
                }
            }
            DB::commit();
            CacheService::cacheAdminGrants($id_u, 'delete');
            return $result;
        });
    }

    /**
     * @param User $user
     * @param int $id_u
     * @return mixed
     */
    public function deleteAdmin(User $user, int $id_u)
    {
        return DB::transaction(function () use ($user, $id_u) {
            $admins = $this->adminInformationRepository->getAdminsInfo($id_u);
            $result = 0;
            foreach ($admins as $admin) {
                if (AdminInformationPolicy::canDelete($user, $admin)) {
                    $result += $admin->delete();
                }
            }
            DB::commit();
            CacheService::cacheAdminGrants($id_u, 'delete');
            return $result;
        });
    }
}
