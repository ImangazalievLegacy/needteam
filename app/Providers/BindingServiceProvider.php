<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App;

class BindingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRepositories();
        $this->bindServices();
    }

    protected function bindRepositories()
    {
        App::bind('App\Repositories\User\UserRepositoryInterface', 'App\Repositories\User\DbUserRepository');
        App::bind('App\Repositories\Project\ProjectRepositoryInterface', 'App\Repositories\Project\DbProjectRepository');
    }

    protected function bindServices()
    {
        App::bind('App\Services\AccountServiceInterface', 'App\Services\AccountService');
        App::bind('App\Services\ProjectServiceInterface', 'App\Services\ProjectService');
    }
}
