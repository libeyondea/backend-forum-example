<?php

namespace App\Transformers\ListUser;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class ListUserTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'role',
    ];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'user_name' => $user->user_name,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    public function includeRole(User $user)
    {
        $role = $user->role;
        return $this->item($role, new RoleTransformers);
    }
}
