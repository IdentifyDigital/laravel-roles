<?php

namespace IdentifyDigital\Roles\Repositories\Contracts;

use IdentifyDigital\Repositories\RepositoryInterface;

interface RoleRepositoryInterface extends RepositoryInterface
{
    public function getAllAssignableRoles();
}