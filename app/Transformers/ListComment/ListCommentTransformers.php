<?php

namespace App\Transformers\ListComment;

use League\Fractal\TransformerAbstract;
use App\Models\Comment;

class ListCommentTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user', 'post', 'children_comment'
    ];

    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'parent_id' => $comment->parent_id,
            'slug' => $comment->slug,
            'content' => $comment->content,
            'published' => $comment->published,
            'published_at' => $comment->published_at,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
            'total_favorited' => $comment->favoritecomment->count(),
            'favorited' => $comment->isFavorited()
        ];
    }

    public function includeChildrenComment(Comment $comment)
    {
        $childrenComment = $comment->childrenComment()->orderBy('created_at', 'desc')->get();
        return $this->collection($childrenComment,  new ListCommentTransformers);
    }

    public function includePost(Comment $comment)
    {
        $post = $comment->post;
        return $this->item($post, new PostTransformers);
    }

    public function includeUser(Comment $comment)
    {
        $user = $comment->user;
        return $this->item($user, new UserTransformers);
    }
}
