<?php


namespace App\Repositories;


use App\Models\AdminInformation;
use Illuminate\Support\Facades\DB;

class AdminInformationRepository
{
    public function getAdmins(int $id_u = null) {
        if (isset($id_u)) {
            $admins = AdminInformation::where('id_u', $id_u)->get()->groupBy('id_pos');
            if (count($admins) === 0) {
                throw new \Exception("no found");
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

    public function createAdmin(
        int $id_u,
        array $ids_pos
    ) {
        $admins = AdminInformation::where('id_u', $id_u)->get();
        if (count($admins) > 0) {
            throw new \Exception("admin is already exist. Use PUT method for change!");
        }
        return DB::transaction(function () use ($id_u, $ids_pos) {
            $result = [];
            foreach ($ids_pos as $id_pos => $roles) {
                if (!isset($result[$id_pos])) {
                    $result[$id_pos] = [];
                }
                foreach ($roles as $role) {
                    array_push($result[$id_pos], AdminInformation::create([
                        'id_ar' => $role,
                        'id_pos' => $id_pos,
                        'id_u' => $id_u
                    ]));
                }
            }
            DB::commit();
            return $result;
        });
    }

    public function changeAdmin(
        int $id_u,
        array $ids_pos
    ) {
        return DB::transaction(function () use ($id_u, $ids_pos) {
            $admins = $this->getAdmins($id_u);
            $result = [];
            foreach ($admins as $id_pos => $admin_roles) {
                $keyPos = array_search($id_pos, $ids_pos);
                foreach ($admin_roles as $admin_role) {
                    if ($keyPos === false) {
                        $admin_role->delete();
                    } else {
                        $keyAr = array_search($admin_role->id_ar, $ids_pos[$id_pos]);
                        if ($keyAr === false) {
                            $admin_role->delete();
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
                    array_push($result[$id_pos], AdminInformation::create([
                        'id_ar' => $id_ar,
                        'id_pos' => $id_pos,
                        'id_u' => $id_u
                    ]));
                }
            }
            DB::commit();
            return $result;
        });
    }

    public function deleteAdmin(int $id_u) {
        return DB::transaction(function () use ($id_u) {
            $result =  AdminInformation::where('id_u', $id_u)->delete();
            DB::commit();
            return $result;
        });
    }
}
