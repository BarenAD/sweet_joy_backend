<?php


namespace App\Repositories;


use App\Models\AdminGrant;
use App\Models\AdminRole;
use Illuminate\Support\Facades\DB;

class AdminRolesRepository
{
    private function extractIdsFromAdminActions($adminActions) {
        $resultArray = [];
        if (isset($adminActions)) {
            foreach ($adminActions as $action) {
                array_push($resultArray, $action->id_aa);
            }
        }
        return $resultArray;
    }

    public function getRoles(int $id = null) {
        if (isset($id)) {
            $role = AdminRole::findOrFail($id);
            $adminActions = AdminGrant::where('id_ar', $id)->get();
            return [
                'role' => $role,
                'actions' => $this->extractIdsFromAdminActions($adminActions)
            ];
        }
        $adminRoles = AdminRole::all();
        $adminsActions = AdminGrant::all()->groupBy('id_ar');
        $resultArray = [];
        foreach ($adminRoles as $role) {
            array_push($resultArray, [
                'role' => $role,
                'actions' => $this->extractIdsFromAdminActions($adminsActions[$role->id])
            ]);
        }
        return $resultArray;
    }

    public function createRole(string $name, array $actions) {
        return DB::transaction(function () use ($name, $actions) {
            $resultRole = AdminRole::create(['name' => $name]);
            $resultActions = [];
            foreach ($actions as $action) {
                array_push($resultActions, AdminGrant::create([
                    'id_ar' => $resultRole->id,
                    'id_aa' => $action
                ])->id_aa);
            }
            DB::commit();
            return [
                'role' => $resultRole,
                'actions' => $resultActions
            ];
        });
    }

    public function changeRole(int $id, string $name, array $actions) {
        return DB::transaction(function () use ($id, $name, $actions) {
            $adminRole = AdminRole::findOrFail($id);
            $adminRole->fill(['name' => $name])->save();
            $adminActions = AdminGrant::where('id_ar', $id)->get();
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
                array_push($resultActions, AdminGrant::create([
                    'id_ar' => $adminRole->id,
                    'id_aa' => $action
                ])->id_aa);
            }
            DB::commit();
            return [
                'role' => $adminRole,
                'actions' => $resultActions
            ];
        });
    }

    public function deleteRole(int $id) {
        return AdminRole::findOrFail($id)->delete();
    }
}
