<?php

namespace App\Http\Controllers;

use App\Repositories\AdminActionsRepository;

/**
 * Class AdminActionsController
 * @package App\Http\Controllers
 */
class AdminActionsController extends Controller
{
    private AdminActionsRepository $actionsRepository;

    public function __construct(AdminActionsRepository $actionsRepository)
    {
        $this->actionsRepository = $actionsRepository;
    }

    public function getActions()
    {
        return response($this->actionsRepository->getAll(), 200);
    }
}
