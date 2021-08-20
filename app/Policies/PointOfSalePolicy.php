<?php

namespace App\Policies;

use App\Http\Services\AdminGrantsService;
use App\Models\PointOfSale;
use App\Models\ProductInformation;
use App\Models\User;

class PointOfSalePolicy
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

    public static function canUpdate(User $user, PointOfSale $pointOfSale)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$pointOfSale['id']])) {
                return in_array(3, $adminActions[$pointOfSale['id']]);
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
