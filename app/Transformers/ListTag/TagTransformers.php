<?php

namespace App\Transformers\ListTag;

use League\Fractal\TransformerAbstract;
use App\Models\Tag;

class TagTransformers extends TransformerAbstract
{
    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'title' => $tag->title,
            'slug' => $tag->slug,
            'content' => $tag->slug,
            'created_at' => $tag->created_at,
            'updated_at' => $tag->updated_at,
            'total_posts' =>$tag->post->count(),
            'following' => $tag->isFollowing()
        ];
    }
}
