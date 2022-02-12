<?php

namespace App\Policies;

use App\Http\Utils\AdminGrantsUtil;
use App\Models\User;

class DocumentsPolicy
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
