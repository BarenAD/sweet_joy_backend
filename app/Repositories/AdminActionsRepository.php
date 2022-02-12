<?php


namespace App\Repositories;


use App\Models\AdminAction;

class AdminActionsRepository
{
    private AdminAction $model;

    public function __construct(AdminAction $adminAction)
    {
        $this->model = $adminAction;
    }

    public function getAllActions()
    {
        return $this->model::all();
    }
}
