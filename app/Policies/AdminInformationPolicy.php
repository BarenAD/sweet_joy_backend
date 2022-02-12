<?php

namespace App\Policies;

use App\Http\Utils\AdminGrantsUtil;
use App\Models\AdminInformation;
use App\Models\User;

class AdminInformationPolicy
{
    private AdminGrantsUtil $adminGrantsUtil;

    public function __construct(
        AdminGrantsUtil $adminGrantsUtil
    ) {
        $this->adminGrantsUtil = $adminGrantsUtil;
    }

    public function canViewAny($user)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
        return isset($adminActions);
    }

    public function canCreate(User $user, int $id_pos)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if ($adminActions === "is_super_admin") {
                return true;
            } else if (isset($adminActions[$id_pos])) {
                return in_array(1, $adminActions[$id_pos]);
            }
        }
        return false;
    }

    public function canDelete(User $user, AdminInformation $adminInformation)
    {
        $adminActions = $this->adminGrantsUtil->getAdminsGrants($user->id);
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
