<?php

namespace App\Transformers\SiteMap;

use League\Fractal\TransformerAbstract;
use App\Models\Tag;

class TagTransformers extends TransformerAbstract
{
    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'slug' => $tag->slug,
            'created_at' => $tag->created_at,
            'updated_at' => $tag->updated_at,
        ];
    }
}
