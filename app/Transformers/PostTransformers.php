<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Role;

class PostTransformers extends TransformerAbstract
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
            'image' => $post->image,
            'content' => $post->content,
            'published' => $post->published,
            'published_at' => $post->published_at,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'total_comments' =>$post->comment->count(),
        ];
    }

    public function includeUser(Post $post)
    {
        $user = $post->user;
        return $this->item($user, function(User $user) {
            return [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'avatar' => $user->avatar,
                'role' => [
                    'id' => $user->role->id,
                    'title' => $user->role->title,
                    'slug' => $user->role->slug,
                ],
            ];
        });
    }

    public function includeCategory(Post $post)
    {
        $category = $post->category;
        return $this->item($category, function(Category $category) {
            return [
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug
            ];
        });
    }

    public function includeTags(Post $post)
    {
        $tag = $post->tag;
        return $this->collection($tag, function(Tag $tag) {
            return [
                'id' => $tag->id,
                'title' => $tag->title,
                'slug' => $tag->slug
            ];
        });
    }
}
