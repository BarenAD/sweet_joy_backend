<?php

namespace App\Providers;

use App\Repositories\AdminActionsRepository;
use App\Repositories\Interfaces\AdminActionsRepositoryInterfaces;
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
            AdminActionsRepositoryInterfaces::class,
            AdminActionsRepository::class
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
