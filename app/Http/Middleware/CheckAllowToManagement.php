<?php

namespace App\Http\Middleware;

use App\Http\Utils\UserPermissionsUtil;
use Closure;
use Illuminate\Support\Facades\Session;

/**
 * Class CheckAllowToManagement
 * @package App\Http\Middleware
 */
class CheckAllowToManagement
{
    private UserPermissionsUtil $adminGrantsUtil;

    public function __construct(UserPermissionsUtil $adminGrantsUtil)
    {
        $this->adminGrantsUtil = $adminGrantsUtil;
    }

    public function handle($request, Closure $next)
    {
        Session::flash('user_permissions', $this->adminGrantsUtil->getUserPermissions($request->user()->id));
        return $next($request);
    }
}
