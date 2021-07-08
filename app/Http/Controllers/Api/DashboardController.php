<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use App\Models\PostTag;
use App\Models\FollowTag;
use App\Transformers\ListPost\ListPostTransformers;
use App\Transformers\ListUser\ListUserTransformers;
use App\Transformers\ListTag\ListTagTransformers;

class DashboardController extends ApiController
{
    public function listPost(Request $request)
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if ($sortDirection == 'desc') {
            $orderDirection = 'desc';
        } else if ($sortDirection == 'asc') {
            $orderDirection = 'asc';
        } else {
            return $this->respondNotFound();
        }

        $post = Post::whereHas('user', function($q) {
            $q->where('user_name', auth()->user()->user_name);
        });

        if ($sortBy == 'created_at') {
            $post = $post->orderBy('created_at', $orderDirection);
        } else if ($sortBy == 'published_at') {
            $post = $post->orderBy('published_at', $orderDirection);
        } else {
            return $this->respondNotFound();
        }

        $postsCount = $post->get()->count();
        $listPost = fractal($post->skip($offset)->take($limit)->get(), new ListPostTransformers);
        return $this->respondSuccessWithPagination($listPost, $postsCount);
    }

    public function listFavoritedPost(Request $request)
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $post = Post::whereHas('userfavorite', function($q) {
            $q->where('user_name', auth()->user()->user_name);
        });

        $postsCount = $post->get()->count();
        $listPost = fractal($post->skip($offset)->take($limit)->get(), new ListPostTransformers);
        return $this->respondSuccessWithPagination($listPost, $postsCount);
    }

    public function listUserFollower(Request $request)
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $user = User::whereHas('followuser', function($q) {
            $q->where('following_id', auth()->user()->id);
        });

        $usersCount = $user->get()->count();
        $listUser = fractal($user->skip($offset)->take($limit)->get(), new ListUserTransformers);
        return $this->respondSuccessWithPagination($listUser, $usersCount);
    }

    public function listFollowingUser(Request $request)
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $user = User::with('following')->whereHas('following', function($q) {
            $q->where('user_id', auth()->user()->id);
        });

        $usersCount = $user->get()->count();
        $listUser = fractal($user->skip($offset)->take($limit)->get(), new ListUserTransformers);
        return $this->respondSuccessWithPagination($listUser, $usersCount);
    }

    public function listFollowingTag(Request $request)
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $tag = Tag::whereHas('followtag', function($q) {
            $q->where('user_id', auth()->user()->id);
        })->withCount('post')->orderBy('post_count', 'desc')->orderBy('created_at', 'desc');

        $tagsCount = $tag->get()->count();
        $listTagFollowed = fractal($tag->skip($offset)->take($limit)->get(), new ListTagTransformers);
        return $this->respondSuccessWithPagination($listTagFollowed, $tagsCount);
    }
}
