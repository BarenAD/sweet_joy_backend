<?php

namespace App\Policies;

use App\Http\Utils\AdminGrantsUtil;
use App\Models\PointOfSale;
use App\Models\User;

class PointOfSalePolicy
{
    private AdminGrantsUtil $adminGrantsUtil;

    public function __construct(
        AdminGrantsUtil $adminGrantsUtil
    ) {
        $this->adminGrantsUtil = $adminGrantsUtil;
    }

    public function canCreate(User $user)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            }
        }
        return false;
    }

    public function canUpdate(User $user, PointOfSale $pointOfSale)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$pointOfSale['id']])) {
                return in_array(3, $adminActions[$pointOfSale['id']]);
            }
        }
        return false;
    }

    public function canDelete(User $user)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            }
        }
        return false;
    }
}
