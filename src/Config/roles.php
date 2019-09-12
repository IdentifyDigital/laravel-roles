<?php

return [

    'roles' => [
        /*
        |--------------------------------------------------------------------------
        | Role Definitions
        |--------------------------------------------------------------------------
        |
        | This is where you should define any roles that will be used in the
        | application. These should include; label and description as well as name
        | for the key.
        */

        'definitions' => [
            'administrator' => ['label' => 'Administrator', 'description' => 'Access to anything and everything', 'assignable' => true],
            'users.create' => ['label' => 'Create Users', 'description' => 'Can create user accounts.'],
            'users.update' => ['label' => 'Update Users', 'description' => 'Can update user accounts.'],
            'users.delete' => ['label' => 'Delete Users', 'description' => 'Can delete user accounts.'],
            'users.show' => ['label' => 'View Users', 'description' => 'Can view user accounts.'],
        ],


        /*
        |--------------------------------------------------------------------------
        | Role Children
        |--------------------------------------------------------------------------
        |
        | Here is where you can place certain roles as children of others. There
        | is no limit to how many parents/children a role can have. If a model
        | has one role, they will also have any child role attached to this!
        */

        'children' => [
            'administrator' => [
                'users.create', 'users.update', 'users.delete', 'users.show'
            ]
        ]
    ]
];