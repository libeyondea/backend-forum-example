<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Resource\Collection;
use App\Models\Post;
use App\Transformers\PostTransformers;

class PostController extends Controller
{
    private $post;
    private $postTransformers;

    public function __construct(Post $post, PostTransformers $postTransformers)
    {
        $this->post = $post;
        $this->postTransformers = $postTransformers;
    }

    public function listPost(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        if ($request->tag) {
            $post = $this->post->whereHas('tag', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
            $postsCount = $post->get()->count();
        } else if ($request->user) {
            $post = $this->post->whereHas('user', function($q) use ($request) {
                $q->where('user_name', $request->user);
            });
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
            $postsCount = $post->get()->count();
        } else if ($request->favorited) {
            $post = $this->post->whereHas('userfavorite', function($q) use ($request) {
                $q->where('user_name', $request->favorited);
            });
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
            $postsCount = $post->get()->count();
        } else {
            $post = $this->post;
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
            $postsCount = $post->get()->count();
        }
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
        $post = $this->post->where('slug', $slug);
        $singlePost = fractal($post->first(), $this->postTransformers);
        return response()->json([
            'success' => true,
            'data' => $singlePost
        ], 200);
    }
}
