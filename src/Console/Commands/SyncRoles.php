<?php

namespace IdentifyDigital\Roles\Console\Commands;

use IdentifyDigital\Roles\Models\Role;
use Illuminate\Console\Command;

class SyncRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'identifydigital:sync-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs the database with roles defined in the config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $definitionsConfig = config('identifydigital.roles.definitions');
        $childrenConfig = config('identifydigital.roles.children');

        //Creating/updating any roles defined in config
        foreach($definitionsConfig as $name => $definition)
            Role::updateOrCreate(
                ['name' => $name],
                [
                    'label' => $definition['label'],
                    'description' => $definition['description'],
                    'assignable' => isset($definition['assignable']) ? $definition['assignable'] : false,
                ]);

        $roles = Role::all();
        foreach($roles as $role) {

            //If this role doesn't exist in the config, set this to inactive
            if(!isset($definitionsConfig[$role->name]))
                $role->delete();

            //If there are no children defined, remove any existing
            if(!isset($childrenConfig[$role->name])) {
                $role->roles()->detach();
            } else {
                //Else, add for each child
                $children = Role::whereIn('name', $childrenConfig[$role->name])->get();
                $toSync = [];
                foreach($children as $child)
                    $toSync[$child->id] = ['relation' => Role::class];
                $role->roles()->sync($toSync);
            }
        }
    }
}
