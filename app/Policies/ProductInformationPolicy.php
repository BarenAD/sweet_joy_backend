<?php

namespace App\Policies;

use App\Http\Services\AdminGrantsService;
use App\Models\ProductInformation;
use App\Models\User;

class ProductInformationPolicy
{
    public static function canCreate(User $user, int $id_pos)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$id_pos])) {
                return in_array(2, $adminActions[$id_pos]);
            }
        }
        return false;
    }

    public static function canUpdateDelete(User $user, ProductInformation $productInformation)
    {
        $adminActions = AdminGrantsService::getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$productInformation['id_pos']])) {
                return in_array(2, $adminActions[$productInformation['id_pos']]);
            }
        }
        return false;
    }
}
