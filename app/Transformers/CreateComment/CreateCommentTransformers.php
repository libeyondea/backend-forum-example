<?php

namespace App\Transformers\CreateComment;

use League\Fractal\TransformerAbstract;
use App\Models\Comment;

class CreateCommentTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'post', 'user'
    ];

    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'parent_id' => $comment->parent_id,
            'title' => $comment->title,
            'slug' => $comment->slug,
            'content' => $comment->content,
            'published' => $comment->published,
            'published_at' => $comment->published_at,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
            'total_favorited' => 0,
            'favorited' => false,
            'children_comment' => []
        ];
    }

    public function includePost(Comment $comment)
    {
        $post = $comment->post;
        return $this->item($post, new PostTransformers);
    }

    public function includeUser(Comment $comment)
    {
        $user = $comment->user;
        return $this->item($user,  new UserTransformers);
    }
}
