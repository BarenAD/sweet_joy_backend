<?php

namespace App\Policies;

use App\Http\services\AdminGrantsService;
use App\Models\AdminInformation;
use App\Models\User;

class AdminInformationPolicy
{
    private $adminGrantsService;

    public function __construct(AdminGrantsService $adminGrantsService)
    {
        $this->adminGrantsService = $adminGrantsService;
    }

    public function viewAny($user)
    {
        //
    }

    public function canCreate($user, int $id_pos)
    {
        $adminActions = $this->adminGrantsService->getAdminsGrants($user->id);
        if (isset($adminActions)) {
            if (isset($adminActions[$id_pos])) {
                return in_array(1, $adminActions[$id_pos]);
            }
        }
        return false;
    }

    public function update(User $user, AdminInformation $adminInformation)
    {
        //
    }

    public function delete(User $user, AdminInformation $adminInformation)
    {
        //
    }
}
