<?php

namespace IdentifyDigital\Roles;

use IdentifyDigital\Roles\Console\Commands\CreateRole;
use IdentifyDigital\Roles\Console\Commands\SyncRoles;
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
