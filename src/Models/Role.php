<?php

namespace IdentifyDigital\Roles\Models;

use IdentifyDigital\Roles\Traits\Assignable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Assignable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['label', 'name', 'description', 'assignable'];
}
