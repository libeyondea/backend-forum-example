<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use App\Models\Post;
use App\Transformers\ListComment\ListCommentTransformers;
use App\Http\Requests\Api\CreateCommentRequest;

class CommentController extends ApiController
{
    public function listComment(Request $request, $limit = 5, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $comment = Comment::whereHas('post', function($q) use ($request) {
            $q->where('slug', $request->post_slug);
        });

        $commentsCount = $comment->get()->count();
        $listComment = fractal($comment->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get(), new ListCommentTransformers);
        return $this->respondSuccessWithPagination($listComment, $commentsCount);
    }

    public function createComment(CreateCommentRequest $request)
    {
        $comment = new Comment;
        $comment->post_id = Post::where('slug', $request->post_slug)->first()->id;
        $comment->user_id = auth()->user()->id;
        $comment->content = $request->content;
        $comment->published = 1;
        $comment->save();
        return $this->respondSuccess($comment);
    }

    public function deleteComment(Request $request, $id)
    {
        $comment = Comment::where('post_id', Post::where('slug', $request->post_slug)->first()->id)
                            ->where('user_id', auth()->user()->id)
                            ->where('id', $id)->first();
        $comment->delete();
        return $this->respondSuccess($comment);
    }
}
