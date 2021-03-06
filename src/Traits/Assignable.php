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
            ->withPivot('relation', 'relation_id', 'user_group_id')
            ->wherePivot('relation', self::class);
    }

    /**
     * Assigns the current model to the given role.
     *
     * @param $role
     * @param int|null $userGroupId
     * @return array
     */
    public function assign($role, $userGroupId = null)
    {
        $role = $this->__role_factory($role, $userGroupId);
        return $this->roles()->syncWithoutDetaching([$role->getKey() => [
            'relation' => self::class,
            'user_group_id' => $userGroupId
        ]]);
    }

    /**
     * Revokes the given role from the current model.
     *
     * @param $role
     * @param int|null $userGroupId
     * @return int
     */
    public function revoke($role, $userGroupId = null)
    {
        $role = $this->__role_factory($role);
        return $this->roles()->wherePivot('user_group_id', $userGroupId)->detach($role);
    }

    /**
     * Checks whether the current model has the given $search role either directly or indirectly.
     *
     * @param $search
     * @param bool $direct
     * @param int|null $userGroupId
     * @return bool
     */
    public function hasRole($search, $direct = false, $userGroupId = null)
    {
        //We'll start off by checking whether the current model has a role_id attribute
        if(isset($this->attributes['role_id']) && !empty($this->attributes['role_id'])) {

            //If we can check straight away for a simple "role has role" relation we will do
            if($this->role()->whereHas('roles', function ($query) use ($search) {
                if($search instanceof Role)
                    $query->where('name', '=', $search->name);
                elseif(is_numeric($search))
                    $query->where('roles.id', '=', $search);
                else
                    $query->where('name', '=', $search);
            })->exists())
                return true;
        }

        $search = $this->__role_factory($search);

        //Getting all the user roles for the current model.
        $allRoles = $this->roles()->wherePivot('user_group_id', $userGroupId)->get();

        //First, checking whether the model itself has the $search role directly
        if($allRoles->contains($search))
            return true;

        //If there is no role attached found and we are searching for direct
        //matches we should return false here
        elseif($direct === true)
            return false;

        //As we are not searching directly, we can check if any of the assigned
        //roles to this model has the $search role within
        foreach($allRoles as $role) {

            //If this child role has the $search role, return true
            if($role->hasRole($search))
                return true;
        }

        //Once we are getting here we haven't found the $search role anywhere, we should return false
        return false;

    }

    /**
     * Returns the name of the first assignable role attached to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Returns the Role model from either a name or object ID.
     *
     * @param $role
     * @param $userGroupId
     * @return mixed
     */
    private function __role_factory($role, $userGroupId = null)
    {
        //First checking if the $role is already an object.
        if($role instanceof Role)
            return $role;

        //Looking for any ID given to find the role.
        if(is_int($role) || is_numeric($role))
            return Role::findOrFail($role);

        //Else we should search by name to find the role.
        return Role::query()
            ->where('name', $role)
            ->when($userGroupId, function ($query, $keyword) {
                $query->where('user_group_id', '=', $keyword);
            })
            ->firstOrFail();
    }
}
