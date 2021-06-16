<?php

namespace App\Transformers\CreateComment;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformers extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }
}
