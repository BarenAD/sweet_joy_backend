<?php


namespace App\Repositories;


use App\Models\AdminAction;

class AdminActionsRepository
{
    public static function getAllActions() {
        return AdminAction::all();
    }
}
