<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AdminActionsRepositoryInterface;
use Illuminate\Http\Request;

class AdminActionsController extends Controller
{
    private $adminActionsRepository;
    public function __construct(AdminActionsRepositoryInterface $adminActionsRepository)
    {
        $this->adminActionsRepository = $adminActionsRepository;
    }

    public function getActions() {
        return response($this->adminActionsRepository->getAllActions(), 200);
    }
}
