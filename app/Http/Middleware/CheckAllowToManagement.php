<?php

namespace App\Http\Middleware;

use App\Http\services\AdminGrantsService;
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
            abort(403, 'Вы не являетесь администратором.');
        }
        return $next($request);
    }
}
