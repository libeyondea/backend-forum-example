<?php

namespace App\Transformers\ListTagWithPost;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformers extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'user_name' => $user->user_name
        ];
    }

}
