<?php

namespace IdentifyDigital\Roles\Console\Commands;

use IdentifyDigital\Roles\Models\Role;
use Illuminate\Console\Command;

class CreateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:role {name} {--label=} {--description=} {--assignable}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new role';

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
        Role::create([
            'name' => $this->argument('name'),
            'label' => $this->option('label'),
            'description' => $this->option('description'),
            'assignable' => $this->option('assignable'),
        ]);
    }
}
