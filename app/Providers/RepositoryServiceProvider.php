<?php

namespace App\Providers;

use App\Repositories\AdminActionsRepository;
use App\Repositories\AdminRolesRepository;
use App\Repositories\Interfaces\AdminActionsRepositoryInterface;
use App\Repositories\Interfaces\AdminRolesRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AdminActionsRepositoryInterface::class,
            AdminActionsRepository::class
        );
        $this->app->bind(
            AdminRolesRepositoryInterface::class,
            AdminRolesRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
