<?php

namespace App\Transformers\ListComment;

use League\Fractal\TransformerAbstract;
use App\Models\Post;

class PostTransformers extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
        ];
    }
}
