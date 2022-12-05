<?php

namespace App\Http\Middleware;

use App\Http\Utils\UserPermissionUtil;
use Closure;
use Illuminate\Support\Facades\Session;

class SetUpAbilities
{
    private UserPermissionUtil $adminGrantsUtil;

    public function __construct(UserPermissionUtil $adminGrantsUtil)
    {
        $this->adminGrantsUtil = $adminGrantsUtil;
    }

    public function handle($request, Closure $next)
    {
        Session::flash('user_permissions', $this->adminGrantsUtil->getUserPermissions($request->user()->id));
        return $next($request);
    }
}
