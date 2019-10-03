<?php

namespace IdentifyDigital\Roles\Repositories;

use IdentifyDigital\Repositories\Repository;
use IdentifyDigital\Roles\Models\Role;

class RoleRepositories extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Role::class;
    }
}