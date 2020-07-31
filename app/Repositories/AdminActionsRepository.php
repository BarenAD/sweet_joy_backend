<?php


namespace App\Repositories;


use App\AdminAction;
use App\Repositories\Interfaces\AdminActionsRepositoryInterface;

class AdminActionsRepository implements AdminActionsRepositoryInterface
{
    private $model;

    public function __construct(AdminAction $model)
    {
        $this->model = $model;
    }

    public function getAllActions() {
        return $this->model::all();
    }
}
