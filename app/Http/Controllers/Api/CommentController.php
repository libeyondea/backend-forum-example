<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use App\Models\Post;
use App\Transformers\ListComment\ListCommentTransformers;

class CommentController extends Controller
{
    private $listCommentTransformers;

    public function __construct(ListCommentTransformers $listCommentTransformers)
    {
        $this->listCommentTransformers = $listCommentTransformers;
    }

    public function listComment(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $comment = Comment::whereHas('post', function($q) use ($request) {
            $q->where('slug', $request->post_slug);
        });
        $commentsCount = $comment->get()->count();
        $listComment = fractal($comment->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get(), $this->listCommentTransformers);
        return response()->json([
            'success' => true,
            'data' => $listComment,
            'meta' => [
                'comments_count' => $commentsCount
            ]
        ], 200);
    }

    public function createComment(Request $request)
    {
        $rules = [
            'content' => 'required'
        ];
        $messages = [
            'content.required' => 'Content is required'
        ];
        $payload = [
            'post_id' => Post::where('slug', $request->post_slug)->first()->id,
            'user_id' => auth()->user()->id,
            'content' => $request->content,
            'published' => 1
        ];
        $validator = Validator::make($payload, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        $comment = new Comment($payload);
        $comment->save();
        $comment = fractal($comment, $this->listCommentTransformers);
        return response()->json([
            'success' => true,
            'data' => $comment,
        ], 200);
    }

    public function deleteComment(Request $request, $id)
    {
        $comment = Comment::where('post_id', Post::where('slug', $request->post_slug)->first()->id)
                            ->where('user_id', auth()->user()->id)
                            ->where('id', $id)->first();
        $comment->delete();
        return response()->json([
            'success' => true,
            'data' => $comment,
        ], 200);
    }
}
