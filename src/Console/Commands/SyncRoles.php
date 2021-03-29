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
                [
                    'name' => $name,
                    'user_group_id' => null
                ],
                [
                    'label' => $definition['label'],
                    'description' => $definition['description'],
                    'assignable' => isset($definition['assignable']) ? $definition['assignable'] : false,
                ]);

        //Getting all the current roles now in the database
        $roles = Role::query()->whereNull('user_group_id')->get();
        foreach($roles as $role) {

            //If this role doesn't exist in the config, set this to inactive
            if(!isset($definitionsConfig[$role->name]))
                $role->delete();

            if($childrenConfig !== false) {
                //If there are no children defined, remove any existing
                if (!isset($childrenConfig[$role->name])) {
                    $role->roles()->detach();
                } else {
                    //Getting all roles defined so that we can filter by these if needed
                    $definitionKeys = array_keys($definitionsConfig);
                    //Setting the array that will be used for attaching the children to this role
                    $children_names = $childrenConfig[$role->name];

                    //Looping through each of the child roles under the current role
                    foreach ($children_names as $child_key => $child_name) {
                        //If the role is an asterisk on its own we should just add all the roles as children
                        if ($child_name === '*') {
                            $children_names = Role::where('assignable', false)->pluck('name', 'id')->toArray();
                            continue;
                        } elseif (strpos($child_name, '*') !== false) {
                            unset($children_names[$child_key]);
                            //Getting the text before the '*' so we can get any roles that include this string
                            $search = str_replace("*", "", $child_name);
                            //For each of the definition roles we should check whether this starts with the $search variable
                            //And if it does we will add this to the children_names array
                            foreach ($definitionKeys as $role_name) {
                                if (strpos($role_name, $search) === 0) {
                                    array_push($children_names, $role_name);
                                }
                            }
                        }
                    }
                    //Removing any duplicate roles
                    $children_names = array_unique($children_names);

                    //add for each child
                    $children = Role::whereIn('name', $children_names)->get();
                    $toSync = [];
                    foreach ($children as $child)
                        $toSync[$child->id] = ['relation' => Role::class];

                    $role->roles()->sync($toSync);
                }
            }
        }
    }
}
