<?php

namespace App;

use Laratrust\LaratrustPermission;

class Permission extends LaratrustPermission
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name'];  
}
