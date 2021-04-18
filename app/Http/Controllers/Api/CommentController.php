<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Transformers\CommentTransformers;

class CommentController extends Controller
{
    private $comment;

    private $commentTransformers;

    public function __construct(Comment $comment, CommentTransformers $commentTransformers)
    {
        $this->comment = $comment;
        $this->commentTransformers = $commentTransformers;
    }

    public function listComment(Request $request, $post_slug, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $comment = $this->comment->whereHas('post', function($q) use ($post_slug) {
            $q->where('slug', $post_slug);
        });
        $listComment = fractal($comment->skip($offset)->take($limit)->get(), $this->commentTransformers);
        $commentsCount = $comment->get()->count();
        return response()->json([
            'success' => true,
            'data' => $listComment,
            'meta' => [
                'comments_count' => $commentsCount
            ]
        ], 200);
    }
}
