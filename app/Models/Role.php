<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id';

    public function RolePermission()
    {
    	return $this->hasMany('App\Models\RolePermission', 'role_id', 'id');
    }

    public function Permission()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_permission');
    }

    public function User()
    {
    	return $this->hasMany('App\Models\User', 'role_id', 'id');
    }
}
