<?php

namespace App\Transformers\EditProfile;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class EditProfileTransformers extends TransformerAbstract
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
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'gender' => $user->gender,
            'biography' => $user->biography,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'verified' => $user->isVerified(),
        ];
    }

    public function includeRole(User $user)
    {
        $role = $user->role;
        return $this->item($role, new RoleTransformers);
    }
}
