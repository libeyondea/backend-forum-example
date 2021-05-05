<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Transformers\ListPost\ListPostTransformers;
use App\Transformers\SinglePost\SinglePostTransformers;

class PostController extends Controller
{
    private $listPostTransformers;

    private $singlePostTransformers;

    public function __construct(ListPostTransformers $listPostTransformers, SinglePostTransformers $singlePostTransformers)
    {
        $this->listPostTransformers = $listPostTransformers;
        $this->singlePostTransformers = $singlePostTransformers;
    }

    public function listPost(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc')
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $field = $request->input('sort_field', $field);
        $type = $request->input('sort_type', $type);

        if ($request->has('tag')) {
            $post = Post::whereHas('tag', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        } else if ($request->has('category')) {
            $post = Post::whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        } else if ($request->has('user')) {
            $post = Post::whereHas('user', function($q) use ($request) {
                $q->where('user_name', $request->user);
            });
        } else if ($request->has('favorited')) {
            $post = Post::whereHas('userfavorite', function($q) use ($request) {
                $q->where('user_name', $request->favorited);
            });
        } else {
            $post = new Post;
        }
        $postsCount = $post->get()->count();
        $listPost = fractal($post->orderBy($field, $type)->skip($offset)->take($limit)->get(), $this->listPostTransformers);
        return response()->json([
            'success' => true,
            'data' => $listPost,
            'meta' => [
                'posts_count' => $postsCount
            ]
        ], 200);
    }

    public function singlePost($slug)
    {
        $post = Post::where('slug', $slug);
        $singlePost = fractal($post->first(), $this->singlePostTransformers);
        return response()->json([
            'success' => true,
            'data' => $singlePost
        ], 200);
    }
}
