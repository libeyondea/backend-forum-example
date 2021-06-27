<?php

namespace App\Transformers\SiteMap;

use League\Fractal\TransformerAbstract;
use App\Models\Post;

class PostTransformers extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'slug' => $post->slug,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'user' => [
                'user_name' => $post->user->user_name
            ]
        ];
    }
}
