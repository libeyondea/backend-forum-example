<?php

namespace App\Transformers\ListPost;

use League\Fractal\TransformerAbstract;
use App\Models\Post;

class ListPostTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user', 'category', 'tags'
    ];

    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'parent_id' => $post->parent_id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'ghim' => $post->ghim,
            'published' => $post->published,
            'published_at' => $post->published_at,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'total_comments' =>$post->comment->count(),
            'total_favorited' =>$post->favoritepost->count(),
            'favorited' => $post->isFavorited()
        ];
    }

    public function includeUser(Post $post)
    {
        $user = $post->user;
        return $this->item($user, new UserTransformers);
    }

    public function includeCategory(Post $post)
    {
        $category = $post->category;
        return $this->item($category,  new CategoryTransformers);
    }

    public function includeTags(Post $post)
    {
        $tag = $post->tag;
        return $this->collection($tag,  new TagTransformers);
    }
}
