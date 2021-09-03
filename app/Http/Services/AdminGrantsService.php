<?php


namespace App\Http\Services;


use App\Models\AdminInformation;
use App\Models\AdminRole;
use App\Models\SuperAdmin;

class AdminGrantsService
{
    public static function getAdminsGrants(int $idUser)
    {
        $result = CacheService::cacheAdminGrants($idUser, 'get');
        if (isset($result)) {
            return $result;
        } else {
            $result = [];
            try {
                $isSuperAdmin = SuperAdmin::where('id_u', $idUser)->first();
                if (isset($isSuperAdmin)) {
                    $result = "is_super_admin";
                } else {
                    $adminInfo = AdminInformation::where('id_u', $idUser)->get()->toArray();
                    if (count($adminInfo) === 0) {
                        $result = null;
                    } else {
                        $adminRoles = AdminRole::all();

                        foreach ($adminInfo as $id_point_of_sale => $arrayRoles) {
                            $result[$id_point_of_sale] = [];
                            foreach ($arrayRoles as $role) {
                                $result[$id_point_of_sale] = array_merge($adminRoles[$role['id_ar']], $result[$id_point_of_sale]);
                            }
                            $result[$id_point_of_sale] = array_unique($result[$id_point_of_sale]);
                        }
                    }
                }
                CacheService::cacheAdminGrants($idUser, 'create', $result);
                return $result;
            } catch (\Exception $e) {
                return null;
            }
        }
    }
}
