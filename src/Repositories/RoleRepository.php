<?php

namespace IdentifyDigital\Roles\Repositories;

use IdentifyDigital\Repositories\Repository;
use IdentifyDigital\Roles\Repositories\Contracts\RoleRepositoryInterface;
use IdentifyDigital\Roles\Models\Role;
use Illuminate\Support\Collection;

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

    /**
     * Returns a collection of the assignable roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllAssignableRoles()
    {
        return $this->model->where('assignable', 1)->orderBy('name', 'DESC')->get();
    }


    /**
     * Returns a collection of the non-assignable roles.
     *
     * @return Collection
     */
    public function getAllNoneAssignableRoles()
    {
        return $this->model->where('assignable', 0)->orderBy('name', 'DESC')->get();
    }
}
