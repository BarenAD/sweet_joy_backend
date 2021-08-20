<?php

namespace App\Http\Middleware;

use App\Http\Services\AdminGrantsService;
use App\Http\Services\GeneratedAborting;
use Closure;

class CheckAllowToManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $adminGrants = AdminGrantsService::getAdminsGrants($request->user()->id);
        if (!isset($adminGrants)) {
            GeneratedAborting::youAreNotAdmin();
        }
        return $next($request);
    }
}
