<?php

namespace App\Transformers\SinglePost;

use League\Fractal\TransformerAbstract;
use App\Models\Tag;

class TagTransformers extends TransformerAbstract
{
    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'title' => $tag->title,
            'slug' => $tag->slug
        ];
    }
}
