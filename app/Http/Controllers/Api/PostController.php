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
            $post = Post::whereHas('tag', function($q) use ($request) {
                $q->where('slug', $request->tag);
            })->orderBy('id', 'asc');
            $postsCount = $post->get()->count();
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
        } else if ($request->category) {
            $post = Post::whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            })->orderBy('id', 'asc');
            $postsCount = $post->get()->count();
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
        } else if ($request->user) {
            $post = Post::whereHas('user', function($q) use ($request) {
                $q->where('user_name', $request->user);
            })->orderBy('id', 'asc');
            $postsCount = $post->get()->count();
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
        } else if ($request->favorited) {
            $post = Post::whereHas('userfavorite', function($q) use ($request) {
                $q->where('user_name', $request->favorited);
            })->orderBy('id', 'asc');
            $postsCount = $post->get()->count();
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
        } else {
            $post = Post::orderBy('id', 'asc');
            $postsCount = $post->get()->count();
            $listPost = fractal($post->skip($offset)->take($limit)->get(), $this->postTransformers);
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
        $post = Post::where('slug', $slug);
        $singlePost = fractal($post->first(), $this->postTransformers);
        return response()->json([
            'success' => true,
            'data' => $singlePost
        ], 200);
    }
}
