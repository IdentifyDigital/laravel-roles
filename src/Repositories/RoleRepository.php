<?php

namespace IdentifyDigital\Roles\Repositories;

use IdentifyDigital\Repositories\Repository;
use IdentifyDigital\Roles\Repositories\Contracts\RoleRepositoryInterface;
use IdentifyDigital\Roles\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
     * Creates a new model with the given data.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        if(!isset($data['name']))
            $data['name'] = Str::slug($data['label']);

        return parent::create($data);
    }

    /**
     * Returns a collection of the assignable roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllAssignableRoles()
    {
        $this->applyCriteria();

        return $this->model->where('assignable', 1)->orderBy('name', 'DESC')->get();
    }


    /**
     * Returns a collection of the non-assignable roles.
     *
     * @return Collection
     */
    public function getAllNoneAssignableRoles()
    {
        $this->applyCriteria();

        return $this->model->where('assignable', 0)->orderBy('name', 'DESC')->get();
    }
}
