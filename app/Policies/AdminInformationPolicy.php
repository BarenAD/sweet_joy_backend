<?php

namespace App\Policies;

use App\Http\Services\AdminGrantsService;
use App\Models\AdminInformation;
use App\Models\User;

class AdminInformationPolicy
{
    public static function canViewAny($user)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        return isset($adminActions);
    }

    public static function canCreate(User $user, int $id_pos)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$id_pos])) {
                return in_array(1, $adminActions[$id_pos]);
            }
        }
        return false;
    }

    public static function canDelete(User $user, AdminInformation $adminInformation)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$adminInformation['id_pos']])) {
                return in_array(1, $adminActions[$adminInformation['id_pos']]);
            }
        }
        return false;
    }
}
