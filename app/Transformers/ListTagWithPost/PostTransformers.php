<?php

namespace App\Transformers\ListTagWithPost;

use League\Fractal\TransformerAbstract;
use App\Models\Post;

class PostTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user'
    ];

    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'total_comments' =>$post->comment->count()
        ];
    }

    public function includeUser(Post $post)
    {
        $user = $post->user;
        return $this->item($user, new UserTransformers);
    }
}
