<?php

namespace App\Policies;

use App\Http\Services\AdminGrantsService;
use App\Models\User;

class SchedulesPolicy
{
    public static function canCreate(User $user)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            }
        }
        return false;
    }

    public static function canUpdate(User $user)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            }
        }
        return false;
    }

    public static function canDelete(User $user)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            }
        }
        return false;
    }
}
