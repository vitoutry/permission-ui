<?php

namespace Vitoutry\PermissionUI\Providers;

use Illuminate\Support\ServiceProvider;

class PermissionUIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      //
        $this->app->make('Vitoutry\PermissionUI\Controllers\PermissionUIController');
        $this->app->make('Vitoutry\PermissionUI\Controllers\RoleController');
       
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__.'/../Routes'.'/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->loadViewsFrom(__DIR__.'/../'.'/views', 'PermissionUI');
        $this->publishes([
            __DIR__.'/../views' => base_path('resources/views/vitoutry/permissionui'),
        ]);
    }
}
