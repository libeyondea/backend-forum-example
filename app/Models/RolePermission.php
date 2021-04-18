<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permission';
    protected $primaryKey = ['role_id', 'permission_id'];

    public function Role()
    {
    	return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function Permission()
    {
    	return $this->belongsTo('App\Models\Permission', 'permission_id', 'id');
    }
}
