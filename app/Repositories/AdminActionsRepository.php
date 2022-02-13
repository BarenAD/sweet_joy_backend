<?php


namespace App\Repositories;


use App\Models\AdminAction;

class AdminActionsRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return AdminAction::class;
    }
}
