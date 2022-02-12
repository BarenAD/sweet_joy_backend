<?php


namespace App\Http\Utils;

use App\Models\AdminInformation;
use App\Models\AdminRole;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Cache;

class AdminGrantsUtil
{
    public function getAdminsGrants(int $idUser)
    {
        return Cache::remember('cache_admin_grants_user_' . $idUser, 3600, function () use ($idUser) {
            $result = [];
            $isSuperAdmin = SuperAdmin::where('id_u', $idUser)->first();
            if (isset($isSuperAdmin)) {
                return ["is_super_admin"];
            }
            $adminInfo = AdminInformation::where('id_u', $idUser)->groupBy('id_pos')->get();
            if (count($adminInfo) === 0) {
                throw new \Exception("Не является администратором", 0);
            }
            $adminRoles = AdminRole::all();
            foreach ($adminInfo as $idPointOfSale => $roles) {
                $result[$idPointOfSale] = [];
                foreach ($roles as $role) {
                    $result[$idPointOfSale][] = $adminRoles->find($role->id_ar);
                }
                $result[$idPointOfSale] = array_unique($result[$idPointOfSale]);
            }
            return $result;
        });
    }
}
