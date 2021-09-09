<?php

namespace App\Http\Controllers;

use App\Repositories\AdminActionsRepository;
use Illuminate\Http\Request;

/**
 * Class AdminActionsController
 * @package App\Http\Controllers
 */
class AdminActionsController extends Controller
{
    private $actionsRepository;

    /**
     * AdminActionsController constructor.
     * @param AdminActionsRepository $actionsRepository
     */
    public function __construct(AdminActionsRepository $actionsRepository)
    {
        $this->actionsRepository = $actionsRepository;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getActions()
    {
        return response($this->actionsRepository->getAllActions(), 200);
    }
}
