<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Follow;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\File;
use App\Transformers\ListPost\ListPostTransformers;
use App\Transformers\SinglePost\SinglePostTransformers;
use App\Http\Requests\Api\CreatePostRequest;
use App\Http\Requests\Api\UpdatePostRequest;

class PostController extends Controller
{
    private $listPostTransformers;

    private $singlePostTransformers;

    public function __construct(ListPostTransformers $listPostTransformers, SinglePostTransformers $singlePostTransformers)
    {
        $this->listPostTransformers = $listPostTransformers;
        $this->singlePostTransformers = $singlePostTransformers;
    }

    public function listPost(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc', $tab = 'feed')
    {
        $user = auth('api')->user();
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $tab = $request->get('tab', $tab);

        if ($tab != 'latest' && $tab != 'oldest' && $tab != 'feed') {
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => 'Not found',
                    'status' => 404,
                    'detail' => 'Not found',
                    'instance' => ''
                ]
            ], 404);
        }

        if ($tab == 'latest') {
            $type = 'desc';
        } else if ($tab == 'oldest') {
            $type = 'asc';
        }

        if ($request->has('tag')) {
            $post = Post::whereHas('tag', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
            if ($tab == 'feed' && $user) {
                $post = $post->where(function($subQuery) use ($user)
                {
                    $subQuery->whereHas('tag', function($q) use ($user) {
                        $q->whereIn('slug',  Tag::select('slug')->whereHas('followtag', function($q) use ($user) {
                            $q->where('user_id',  $user->id);
                        })->get());
                    })->orWhereHas('user', function($q) use ($user) {
                        $q->whereIn('user_name',  User::select('user_name')->whereHas('following', function($q) use ($user) {
                            $q->where('user_id',  $user->id);
                        })->get());
                    });
                });
            }
        } else if ($request->has('category')) {
            $post = Post::whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
            if ($tab == 'feed' && $user) {
                $post = $post->where(function($subQuery) use ($user)
                {
                    $subQuery->whereHas('user', function($q) use ($user) {
                        $q->whereIn('user_name',  User::select('user_name')->whereHas('following', function($q) use ($user) {
                            $q->where('user_id',  $user->id);
                        })->get());
                    })->orWhereHas('tag', function($q) use ($user) {
                        $q->whereIn('slug',  Tag::select('slug')->whereHas('followtag', function($q) use ($user) {
                            $q->where('user_id',  $user->id);
                        })->get());
                    });
                });
            }
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
            if ($tab == 'feed' && $user) {
                $post = $post->whereHas('user', function($q) use ($user) {
                    $q->whereIn('user_name',  User::select('user_name')->whereHas('following', function($q) use ($user) {
                        $q->where('user_id',  $user->id);
                    })->get());
                })->orWhereHas('tag', function($q) use ($user) {
                    $q->whereIn('slug',  Tag::select('slug')->whereHas('followtag', function($q) use ($user) {
                        $q->where('user_id',  $user->id);
                    })->get());
                });
            }
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
        $singlePost = fractal($post->firstOrFail(), $this->singlePostTransformers);
        return response()->json([
            'success' => true,
            'data' => $singlePost
        ], 200);
    }

    public function createPost(CreatePostRequest $request)
    {
        if($request->hasfile('image')) {
            $imageName = time().'.'.$request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);
        } else {
            $imageName = '';
        }

        $createPost = new Post;
        $createPost->category_id = $request->category_id;
        $createPost->user_id = auth()->user()->id;
        $createPost->title = $request->title;
        $createPost->slug = Str::slug($request->title, '-') . '-' . Str::lower(Str::random(4));
        $createPost->excerpt = $request->excerpt;
        $createPost->content = $request->content;
        $createPost->image = $imageName;
        $createPost->published = '1';
        $createPost->published_at = Carbon::now()->toDateTimeString();
        $createPost->save();
        $lastIdPost = $createPost->id;

        foreach ($request->tags as $key => $tags) {
            $convertTitleToSlug = Str::slug($tags['slug'], '-');
            $checkTag = Tag::where('slug', $convertTitleToSlug)->first();
            if (!$checkTag) {
                $newTag = new Tag;
                $newTag->title = $convertTitleToSlug;
                $newTag->slug = $convertTitleToSlug;
                $newTag->content = $convertTitleToSlug;
                $newTag->save();
                $tagId = $newTag->id;
            } else {
                $tagId = Tag::where('slug', $convertTitleToSlug)->first()->id;
            }
            $checkPostTag = PostTag::where('post_id', $lastIdPost)->where('tag_id', $tagId)->first();
            if (!$checkPostTag) {
                $postTag = new PostTag;
                $postTag->post_id = $lastIdPost;
                $postTag->tag_id = $tagId;
                $postTag->save();
            }
        }

        $post = Post::where('id', $lastIdPost);
        $singlePost = fractal($post->firstOrFail(), $this->singlePostTransformers);
        return response()->json([
            'success' => true,
            'data' => $singlePost
        ], 200);
    }

    public function updatePost(UpdatePostRequest $request, $slug)
    {
        $updatePost = Post::where('slug', $slug)->where('user_id', auth()->user()->id)->firstOrFail();
        $updatePost->category_id = $request->category_id;
        $updatePost->user_id = auth()->user()->id;
        $updatePost->title = $request->title;
        $updatePost->slug = Str::slug($request->title, '-') . '-' . Str::lower(Str::random(4));
        $updatePost->excerpt = $request->excerpt;
        $updatePost->content = $request->content;
        $updatePost->published = '1';
        $updatePost->published_at = Carbon::now()->toDateTimeString();

        if($request->hasfile('image')) {
            $oldImage = public_path('images/' . $updatePost->image);
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }
            $imageName = time().'.'.$request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);

            $updatePost->image = $imageName;
        }

        $updatePost->save();

        $lastIdPost = $updatePost->id;

        $deletePostTag = PostTag::where('post_id', $lastIdPost);
        if ($deletePostTag->get()->count() > 0) {
            $deletePostTag->delete();
        }

        foreach ($request->tags as $key => $tags) {
            $convertTitleToSlug = Str::slug($tags['slug'], '-');
            $checkTag = Tag::where('slug', $convertTitleToSlug)->first();
            if (!$checkTag) {
                $newTag = new Tag;
                $newTag->title = $convertTitleToSlug;
                $newTag->slug = $convertTitleToSlug;
                $newTag->content = $convertTitleToSlug;
                $newTag->save();
                $tagId = $newTag->id;
            } else {
                $tagId = Tag::where('slug', $convertTitleToSlug)->first()->id;
            }
            $checkPostTag = PostTag::where('post_id', $lastIdPost)->where('tag_id', $tagId)->first();
            if (!$checkPostTag) {
                $postTag = new PostTag;
                $postTag->post_id = $lastIdPost;
                $postTag->tag_id = $tagId;
                $postTag->save();
            }
        }

        $post = Post::where('id', $lastIdPost);
        $singlePost = fractal($post->firstOrFail(), $this->singlePostTransformers);
        return response()->json([
            'success' => true,
            'data' => $singlePost
        ], 200);
    }

    public function editPost($slug)
    {
        $post = Post::where('slug', $slug)->where('user_id', auth()->user()->id);
        $editPost = fractal($post->firstOrFail(), new SinglePostTransformers);
        return response()->json([
            'success' => true,
            'data' => $editPost
        ], 200);
    }

    public function deletePost($slug)
    {
        $deletePost = Post::where('slug', $slug)->where('user_id', auth()->user()->id)->firstOrFail();
        $deletePost->delete();
        return response()->json([
            'success' => true,
            'data' => $deletePost
        ], 200);
    }
}
