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
    public function listPost(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc')
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $post = Post::whereHas('user', function($q) {
            $q->where('user_name', auth()->user()->user_name);
        });
        $postsCount = $post->get()->count();
        $listPost = fractal($post->orderBy($field, $type)->skip($offset)->take($limit)->get(), new ListPostTransformers);
        return $this->respondSuccessWithPagination($listPost, $postsCount);
    }

    public function listFavoritedPost(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc')
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $post = Post::whereHas('userfavorite', function($q) {
            $q->where('user_name', auth()->user()->user_name);
        });
        $postsCount = $post->get()->count();
        $listPost = fractal($post->orderBy($field, $type)->skip($offset)->take($limit)->get(), new ListPostTransformers);
        return $this->respondSuccessWithPagination($listPost, $postsCount);
    }

    public function listUserFollower(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc')
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $user = User::whereHas('followuser', function($q) {
            $q->where('following_id', auth()->user()->id);
        });
        $usersCount = $user->get()->count();
        $listUser = fractal($user->orderBy($field, $type)->skip($offset)->take($limit)->get(), new ListUserTransformers);
        return $this->respondSuccessWithPagination($listUser, $usersCount);
    }

    public function listFollowingUser(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc')
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $user = User::whereHas('following', function($q) {
            $q->where('user_id', auth()->user()->id);
        });
        $usersCount = $user->get()->count();
        $listUser = fractal($user->orderBy($field, $type)->skip($offset)->take($limit)->get(), new ListUserTransformers);
        return $this->respondSuccessWithPagination($listUser, $usersCount);
    }

    public function listFollowingTag(Request $request, $limit = 10, $offset = 0, $field = 'created_at', $type = 'desc')
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $tag = Tag::whereHas('followtag', function($q) {
            $q->where('user_id', auth()->user()->id);
        });
        $tagsCount = $tag->get()->count();
        $listTagFollowed = fractal($tag->skip($offset)->take($limit)->get(), new ListTagTransformers);
        return $this->respondSuccessWithPagination($listTagFollowed, $tagsCount);
    }
}
