<?php

namespace IdentifyDigital\Roles\Traits;

use IdentifyDigital\Roles\Models\Role;

trait Assignable
{

    /**
     * Returns a collection of roles related to the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_relation', 'relation_id')
            ->withPivot('relation', 'relation_id')
            ->wherePivot('relation', self::class);
    }

    /**
     * Assigns the current model to the given role.
     *
     * @param $role
     * @return array
     */
    public function assign($role)
    {
        $role = $this->__role_factory($role);
        return $this->roles()->syncWithoutDetaching([$role->getKey() => [
            'relation' => self::class
        ]]);
    }

    /**
     * Revokes the given role from the current model.
     *
     * @param $role
     * @return int
     */
    public function revoke($role)
    {
        $role = $this->__role_factory($role);
        return $this->roles()->detach($role);
    }

    /**
     * Checks whether the current model has the given $search role either directly or indirectly.
     *
     * @param $search
     * @param bool $direct
     * @return bool
     */
    public function hasRole($search, $direct = false)
    {
        $search = $this->__role_factory($search);

        //First, checking whether the model itself has the $search role directly
        if($this->roles->contains($search))
            return true;
        //If there is no role attached found and we are searching for direct
        //matches we should return false here
        elseif($direct === true)
            return false;

        //As we are not searching directly, we can check if any of the assigned
        //roles to this model has the $search role within
        foreach($this->roles as $role) {

            //If this child role has the $search role, return true
            if($role->hasRole($search))
                return true;
        }

        //Once we are getting here we haven't found the $search role anywhere, we should return false
        return false;

    }

    /**
     * Returns the Role model from either a name or object ID.
     *
     * @param $role
     * @return mixed
     */
    private function __role_factory($role)
    {
        //First checking if the $role is already an object.
        if($role instanceof Role)
            return $role;

        //Looking for any ID given to find the role.
        if(is_int($role) || is_numeric($role))
            return Role::findOrFail($role);

        //Else we should search by name to find the role.
        return Role::where('name', $role)->firstOrFail();
    }
}