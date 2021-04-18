<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Role;

class RoleTransformers extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'title' => $role->title,
            'slug' => $role->slug,
            'description' => $role->description,
            'active' => $role->active,
            'created_at' => $role->created_at,
            'updated_at' => $role->updated_at,
        ];
    }
}
