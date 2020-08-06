<?php


namespace App\Repositories;


use App\Models\AdminInformation;
use Illuminate\Support\Facades\DB;

class AdminInformationRepository
{
    public function getAdmins(int $id_u = null) {
        if (isset($id_u)) {
            $admins = AdminInformation::where('id_u', $id_u)->get()->toArray();
            if (count($admins) === 0) {
                throw new \Exception("no found");
            }
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
            foreach ($ids_pos as $key => $roles) {
                foreach ($roles as $role) {
                    AdminInformation::create([
                        'id_ar' => $role,
                        'id_pos' => $key,
                        'id_u' => $id_u
                    ]);
                }
            }
            DB::commit();
            $result = [];
            $result[$id_u] = $ids_pos;
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
