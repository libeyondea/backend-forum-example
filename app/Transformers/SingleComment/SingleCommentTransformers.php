<?php

namespace App\Transformers\SingleComment;

use League\Fractal\TransformerAbstract;
use App\Models\Comment;
use App\Transformers\ListComment\ListCommentTransformers;

class SingleCommentTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'post', 'user', 'children_comment'
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
            'total_favorited' => $comment->favoritecomment->count(),
            'favorited' => $comment->isFavorited(),
            'parent_comment' => $comment->parentcomment
        ];
    }

    public function includeChildrenComment(Comment $comment)
    {
        $childrenComment = $comment->childrencomment()->orderBy('created_at', 'desc')->get();
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
        return $this->item($user,  new UserTransformers);
    }
}
