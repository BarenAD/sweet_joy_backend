<?php


namespace App\Repositories;


use App\AdminAction;

class AdminActionsRepository
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