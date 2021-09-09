<?php


namespace App\Repositories;


use App\Models\AdminAction;

class AdminActionsRepository
{
    private $model;

    /**
     * AdminActionsRepository constructor.
     * @param AdminAction $adminAction
     */
    public function __construct(AdminAction $adminAction)
    {
        $this->model = $adminAction;
    }

    /**
     * @return AdminAction[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllActions()
    {
        return $this->model::all();
    }
}
