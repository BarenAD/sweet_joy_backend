<?php


namespace App\Repositories;

use App\Models\AdminInformation;
use App\Models\User;
use App\Policies\AdminInformationPolicy;
use Illuminate\Support\Facades\DB;

class AdminInformationRepository
{
    public static function getAdmins(int $id_u = null) {
        if (isset($id_u)) {
            $admins = AdminInformation::where('id_u', $id_u)->get()->groupBy('id_pos');
            if (count($admins) === 0) {
                abort(404, 'Ничего не найдено');
            }
            return $admins;
        } else {
            $admins = AdminInformation::all()->toArray();
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

    public static function createAdmin(
        User $user,
        int $id_u,
        array $ids_pos
    ) {
        $admins = AdminInformation::where('id_u', $id_u)->get();
        if (count($admins) > 0) {
            abort(409,"admin is already exist. Use PUT method for change!");
        }
        return DB::transaction(function () use ($user, $id_u, $ids_pos) {
            $result = [];
            foreach ($ids_pos as $id_pos => $roles) {
                if (!isset($result[$id_pos])) {
                    $result[$id_pos] = [];
                }
                foreach ($roles as $role) {
                    if (AdminInformationPolicy::canCreate($user, $id_pos)) {
                        array_push($result[$id_pos], AdminInformation::create([
                            'id_ar' => $role,
                            'id_pos' => $id_pos,
                            'id_u' => $id_u
                        ]));
                    }
                }
                if (count($result[$id_pos]) === 0) {
                    unset($result[$id_pos]);
                }
            }
            DB::commit();
            if (count($result) === 0) {
                abort(403,"Недостаточно прав администрирования!");
            }
            return $result;
        });
    }

    public static function changeAdmin(
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
                        array_push($result[$id_pos], AdminInformation::create([
                            'id_ar' => $id_ar,
                            'id_pos' => $id_pos,
                            'id_u' => $id_u
                        ]));
                    }
                    if (count($result[$id_pos]) === 0) {
                        unset($result[$id_pos]);
                    }
                }
            }
            DB::commit();
            CacheRepository::cacheAdminGrants($id_u, 'delete');
            return $result;
        });
    }

    public static function deleteAdmin(User $user, int $id_u) {
        return DB::transaction(function () use ($user, $id_u) {
            $admins = AdminInformation::where('id_u', $id_u)->get();
            $result = 0;
            foreach ($admins as $admin) {
                if (AdminInformationPolicy::canDelete($user, $admin)) {
                    $result += $admin->delete();
                }
            }
            DB::commit();
            CacheRepository::cacheAdminGrants($id_u, 'delete');
            return $result;
        });
    }
}
