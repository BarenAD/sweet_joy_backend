<?php

namespace App\Http\Middleware;

use App\Http\Services\GeneratedAborting;
use App\Http\Utils\AdminGrantsUtil;
use Closure;

/**
 * Class CheckAllowToManagement
 * @package App\Http\Middleware
 */
class CheckAllowToManagement
{
    private AdminGrantsUtil $adminGrantsUtil;

    public function __construct(AdminGrantsUtil $adminGrantsUtil)
    {
        $this->adminGrantsUtil = $adminGrantsUtil;
    }

    public function handle($request, Closure $next)
    {
        try {
            $this->adminGrantsUtil->getAdminsGrants($request->user()->id);
        } catch (\Exception $exception) {
            GeneratedAborting::youAreNotAdmin();
        }
        return $next($request);
    }
}
