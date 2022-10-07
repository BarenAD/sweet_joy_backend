<?php


namespace App\Http\Utils;

use App\Exceptions\NoReportException;
use App\Models\Operator;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class UserPermissionUtil
{
    public function getUserPermissions(int $idUser)
    {
        return Cache::remember('cache_user_' . $idUser . '_permissions', 3600, function () use ($idUser) {
            if (Operator::query()->where('user_id', $idUser)->exists()) {
                return ["*"];
            }
            $userRoles = UserRole::where('user_id', $idUser)->get();
            if (count($userRoles) === 0) {
                throw new NoReportException('is_not_admin');
            }
            $permissions = Role::query()
                ->whereIn('id', $userRoles->pluck('role_id'))
                ->with('permissions')
                ->get();
            return array_unique(Arr::collapse($permissions->pluck('permissions.*.permission')->toArray()));
        });
    }

    public function checkCanActionByPermissions(array $permissions, array $userPermissions): bool
    {
        if (in_array('*', $userPermissions)) {
            return true;
        }
        if (count(array_intersect($permissions, $userPermissions)) > 0) {
            return true;
        }
        $preparedRules = [];
        foreach ($permissions as $keyPermission => $permission) {   //Преобразует все вложенные правила в .*
            $explodePermission = explode('.', $permission);
            array_pop($explodePermission);
            $prefix = '';
            foreach ($explodePermission as $key => $value) {
                if ($prefix !== '') {
                    $prefix .= '.';
                }
                $prefix .= $value;
                $preparedRules[] = $prefix . '.*';
            }
        }
        if (count(array_intersect(array_unique($preparedRules), $userPermissions)) > 0) {
            return true;
        }
        return false;
    }
}
