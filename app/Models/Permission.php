<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission';
    protected $primaryKey = 'id';

    public function RolePermission()
    {
    	return $this->hasMany('App\Models\RolePermission', 'permission_id', 'id');
    }

    public function Role()
    {
        return $this->belongsToMany('App\Models\Role', 'role_permission');
    }
}
