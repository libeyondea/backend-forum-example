<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Follow;
use App\Models\User;
use App\Models\Tag;
use App\Models\FavoritePost;
use Illuminate\Support\Facades\File;
use App\Transformers\ListPost\ListPostTransformers;
use App\Transformers\SinglePost\SinglePostTransformers;
use App\Http\Requests\Api\CreatePostRequest;
use App\Http\Requests\Api\UpdatePostRequest;

class PostController extends ApiController
{
    public function listPost(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc', $tab = 'feed')
    {
        $user = auth('api')->user();
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $tab = $request->get('tab', $tab);

        if ($tab != 'latest' && $tab != 'oldest' && $tab != 'feed') {
            return $this->respondNotFound();
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
            $post = Post::where('ghim', 0);
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
        }

        $postsCount = $post->get()->count();
        $listPost = fractal($post->orderBy($field, $type)->skip($offset)->take($limit)->get(), new ListPostTransformers);
        return $this->respondSuccessWithPagination($listPost, $postsCount);
    }

    public function listPostGhim(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc', $tab = 'feed')
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $post = Post::where('ghim', 1);
        $postsCount = $post->get()->count();
        $listPost = fractal($post->orderBy($field, $type)->skip($offset)->take($limit)->get(), new ListPostTransformers);
        return $this->respondSuccessWithPagination($listPost, $postsCount);
    }

    public function singlePost($slug)
    {
        $post = Post::where('slug', $slug);
        $singlePost = fractal($post->firstOrFail(), new SinglePostTransformers);
        return $this->respondSuccess($singlePost);
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
        $createPost->ghim = '0';
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
        $singlePost = fractal($post->firstOrFail(), new SinglePostTransformers);
        return $this->respondSuccess($singlePost);
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
        $singlePost = fractal($post->firstOrFail(), new SinglePostTransformers);
        return $this->respondSuccess($singlePost);
    }

    public function editPost($slug)
    {
        $post = Post::where('slug', $slug)->where('user_id', auth()->user()->id);
        $editPost = fractal($post->firstOrFail(), new SinglePostTransformers);
        return $this->respondSuccess($editPost);
    }

    public function deletePost($slug)
    {
        $deletePost = Post::where('slug', $slug)->where('user_id', auth()->user()->id)->firstOrFail();
        $deletePost->delete();
        return $this->respondSuccess($deletePost);
    }

    public function favoritePost(Request $request)
    {
        $user = auth()->user();

        $postFavorite = Post::where('slug', $request->slug)->firstOrFail();

        $favoriteCheck = FavoritePost::where('user_id', $user->id)->where('post_id', $postFavorite->id)->first();

        if(!$favoriteCheck) {
            $favorite = new FavoritePost;
            $favorite->user_id = $user->id;
            $favorite->post_id = $postFavorite->id;
            $favorite->save();
            return $this->respondSuccess([
                'id' => $favorite->post->id,
                'slug' => $favorite->post->slug
            ]);
        } else {
            return $this->respondUnprocessableEntity('Post favorited');
        }
    }

    public function unfavoritePost(Request $request)
    {
        $user = auth()->user();

        $postFavorite = Post::where('slug', $request->slug)->firstOrFail();

        $favoriteCheck = FavoritePost::where('user_id', $user->id)->where('post_id', $postFavorite->id)->first();

        if(!!$favoriteCheck) {
            $favoriteCheck->delete();
            return $this->respondSuccess([
                'id' => $favoriteCheck->post->id,
                'slug' => $favoriteCheck->post->slug
            ]);
        } else {
            return $this->respondUnprocessableEntity('Post does not exist or not in the favoritelist');
        }
    }
}
