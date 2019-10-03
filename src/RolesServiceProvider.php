<?php

namespace IdentifyDigital\Roles;

use IdentifyDigital\Repositories\Repositories\Contracts\RoleRepositoryInterface;
use IdentifyDigital\Roles\Console\Commands\CreateRole;
use IdentifyDigital\Roles\Console\Commands\SyncRoles;
use IdentifyDigital\Roles\Repositories\RoleRepository;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Loading the package migrations
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        //Publishing to packages config
        $this->publishes([
            __DIR__.'/Config/roles.php' => config_path('identifydigital.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/Config/roles.php', 'identifydigital.roles'
        );

        //Assigning the commands to the Kernel
        if ($this->app->runningInConsole()) {
            self::__registerCommands();
        }

        //Binding the repositories for this application
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Registers module specific commands into the Laravel kernel.
     *
     * @void
     */
    private function __registerCommands()
    {
        $this->commands([
            CreateRole::class,
            SyncRoles::class
        ]);
    }
}
