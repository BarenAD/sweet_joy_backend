<?php


namespace App\Http\services;


use App\Models\SuperAdmin;
use App\Repositories\AdminInformationRepository;
use App\Repositories\AdminRolesRepository;
use App\Repositories\CacheRepository;

class AdminGrantsService
{
    private $adminRolesRepository;

    public function __construct(
        AdminRolesRepository $adminRolesRepository
    )
    {
        $this->adminRolesRepository = $adminRolesRepository;
    }

    public function getAdminsGrants(int $idUser) {
        $result = CacheRepository::cacheAdminGrants($idUser, 'get');
        if (isset($result)) {
            return $result;
        } else {
            $result = [];
            try {
                $isSuperAdmin = SuperAdmin::where('id_u', $idUser)->first();
                if (isset($isSuperAdmin)) {
                    $result = "is_super_admin";
                } else {
                    $adminInfo = AdminInformationRepository::getAdmins($idUser)->toArray();
                    $adminRoles = $this->adminRolesRepository->getRolesActions();

                    foreach ($adminInfo as $id_point_of_sale => $arrayRoles) {
                        $result[$id_point_of_sale] = [];
                        foreach ($arrayRoles as $role) {
                            $result[$id_point_of_sale] = array_merge($adminRoles[$role['id_ar']], $result[$id_point_of_sale]);
                        }
                        $result[$id_point_of_sale] = array_unique($result[$id_point_of_sale]);
                    }
                }
                CacheRepository::cacheAdminGrants($idUser, 'create', $result);
                return $result;
            } catch (\Exception $e) {
                return null;
            }
        }
    }
}
