<?php

namespace IdentifyDigital\Roles\Repositories;

use IdentifyDigital\Repositories\Repositories\Contracts\RoleRepositoryInterface;
use IdentifyDigital\Repositories\Repository;
use IdentifyDigital\Roles\Models\Role;

class RoleRepository extends Repository implements RoleRepositoryInterface
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