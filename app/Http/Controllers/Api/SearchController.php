<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Tag;
use App\Transformers\ListPost\ListPostTransformers;
use App\Transformers\ListUser\ListUserTransformers;
use App\Transformers\ListComment\ListCommentTransformers;
use App\Transformers\ListTag\ListTagTransformers;
use App\Http\Requests\Api\SearchRequest;
use DB;

class SearchController extends ApiController
{
    public function search(SearchRequest $request)
    {
        $user = auth('api')->user();
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'latest');
        $type = $request->input('type', 'post');

        if ($sort == 'latest') {
            $orderBy = 'desc';
        } else if ($sort  == 'oldest') {
            $orderBy = 'asc';
        } else {
            return $this->respondNotFound();
        }

        if ($type == 'post') {
            $model = Post::where('title', 'LIKE', '%' . $request->search_fields . '%');
            $transformers = new ListPostTransformers;
        } else if ($type == 'user') {
            $model = User::where('user_name', 'LIKE', '%' . $request->search_fields . '%')
                        ->orWhere(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' . $request->search_fields . '%');
            $transformers = new ListUserTransformers;
        } else if ($type == 'comment') {
            $model = Comment::where('content', 'LIKE', '%' . $request->search_fields . '%');
            $transformers = new ListCommentTransformers;
        } else if ($type == 'my_post') {
            if ($user) {
                $model = Post::where('title', 'LIKE', '%' . $request->search_fields . '%')
                            ->where('user_id',  $user->id);
                $transformers = new ListPostTransformers;
            } else {
                return $this->respondUnauthorized();
            }
        } else if ($type == 'tag') {
            $model = Tag::where('title', 'LIKE', '%' . $request->search_fields . '%')
                        ->orWhere('slug', 'LIKE', '%' . $request->search_fields . '%');
            $transformers = new ListTagTransformers;
        } else {
            return $this->respondNotFound();
        }

        $totalCount = $model->get()->count();
        $listModel = fractal($model->orderBy('created_at', $orderBy)->skip($offset)->take($limit)->get(), $transformers);
        return $this->respondSuccessWithPagination($listModel, $totalCount);
    }
}
