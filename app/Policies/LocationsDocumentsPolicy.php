<?php

namespace App\Policies;

use App\Http\Utils\AdminGrantsUtil;
use App\Models\User;

class LocationsDocumentsPolicy
{
    private AdminGrantsUtil $adminGrantsUtil;

    public function __construct(
        AdminGrantsUtil $adminGrantsUtil
    ) {
        $this->adminGrantsUtil = $adminGrantsUtil;
    }

    public function canUpdate(User $user)
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
