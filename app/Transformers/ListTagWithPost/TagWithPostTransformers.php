<?php

namespace App\Transformers\ListTagWithPost;

use League\Fractal\TransformerAbstract;
use App\Models\Tag;

class TagWithPostTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'posts'
    ];

    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'title' => $tag->title,
            'slug' => $tag->slug
        ];
    }

    public function includePosts(Tag $tag)
    {
        $post = $tag->post->skip(0)->take(5);
        return $this->collection($post,  new PostTransformers);
    }
}
