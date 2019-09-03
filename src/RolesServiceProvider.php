<?php

namespace IdentifyDigital\Roles;

use IdentifyDigital\Roles\Console\Commands\CreateRole;
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
            CreateRole::class
        ]);
    }
}
