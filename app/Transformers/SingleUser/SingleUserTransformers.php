<?php

namespace App\Transformers\SingleUser;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class SingleUserTransformers extends TransformerAbstract
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
            'total_posts' => $user->post->count(),
            'total_favorited' => $user->postfavorite->count(),
            'total_comments' => $user->comment->count(),
            'total_tags_followed' => $user->followTag->count(),
            'total_following_users' => $user->followuser->count(),
            'total_user_followers' => $user->following->count(),
            'following' => $user->isFollowing()
        ];
    }

    public function includeRole(User $user)
    {
        $role = $user->role;
        return $this->item($role, new RoleTransformers);
    }
}
