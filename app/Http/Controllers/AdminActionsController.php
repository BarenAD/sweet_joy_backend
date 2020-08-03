<?php

namespace App\Http\Controllers;

use App\Repositories\AdminActionsRepository;
use Illuminate\Http\Request;

class AdminActionsController extends Controller
{
    private $adminActionsRepository;
    public function __construct(AdminActionsRepository $adminActionsRepository)
    {
        $this->adminActionsRepository = $adminActionsRepository;
    }

    public function getActions() {
        return response($this->adminActionsRepository->getAllActions(), 200);
    }
}
