<?php

namespace App\Http\Controllers;

use App\Repositories\AdminActionsRepository;
use Illuminate\Http\Request;

class AdminActionsController extends Controller
{
    public function getActions() {
        return response(AdminActionsRepository::getAllActions(), 200);
    }
}
