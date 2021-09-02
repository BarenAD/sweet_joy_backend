<?php

namespace App\Policies;

use App\Http\Services\AdminGrantsService;
use App\Models\User;

class SiteConfigurationsPolicy
{
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
}
