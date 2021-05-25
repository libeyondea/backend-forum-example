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
use App\Transformers\ListPost\ListPostTransformers;
use App\Transformers\SinglePost\SinglePostTransformers;
use App\Http\Requests\Api\CreatePostRequest;

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
        /* $requestTags = json_decode($request->tags, true);
        $rules = [
            'slug' => 'unique:post',
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5000',
            'tags' => 'required|array|min:1|max:4',
            'tags.*.title' => 'required|string'
        ];
        $messages = [
            'slug.unique' => 'Slug already exists',
            'title.required' => 'Title is required',
            'content.required' => 'Content is required',
            'category_id.required' => 'Category_id is required',
            'image.image' => 'Image must be an image file',
            'image.mimes' => 'Image file must be .png .jpg .jpeg .gif',
            'image.max' => 'Maximum image size to upload is 5000kb',
            'tags.required' => 'Tag is required',
            'tags.array' => 'Tag must be an array',
            'tags.min' => 'Tag must have an item',
            'tags.max' => 'Add up to 4 tags'
        ];
        $payload = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-') . '-' . Str::lower(Str::random(4)),
            'content' => $request->content,
            'image' => $request->image,
            'tags' => $requestTags,
        ];
        $validator = Validator::make($payload, $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => 'Your request parameters did not validate',
                    'status' => 200,
                    'invalid_params' => $validator->errors(),
                    'detail' => 'Your request parameters did not validate',
                    'instance' => ''
                ]
            ], 200);
        } */

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
            $convertTitleToSlug = Str::slug($tags['title'], '-');
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
