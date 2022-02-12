<?php

namespace App\Policies;

use App\Http\Utils\AdminGrantsUtil;
use App\Models\ProductInformation;
use App\Models\User;

class ProductInformationPolicy
{
    private AdminGrantsUtil $adminGrantsUtil;

    public function __construct(
        AdminGrantsUtil $adminGrantsUtil
    ) {
        $this->adminGrantsUtil = $adminGrantsUtil;
    }

    public function canCreate(User $user, int $id_pos)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$id_pos])) {
                return in_array(2, $adminActions[$id_pos]);
            }
        }
        return false;
    }

    public function canUpdateDelete(User $user, ProductInformation $productInformation)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
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
