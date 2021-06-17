<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\FavoriteComment;
use App\Models\Post;
use App\Models\User;
use App\Transformers\ListComment\ListCommentTransformers;
use App\Transformers\SingleComment\SingleCommentTransformers;
use App\Transformers\CreateComment\CreateCommentTransformers;
use App\Http\Requests\Api\CreateCommentRequest;
use App\Http\Requests\Api\UpdateCommentRequest;

class CommentController extends ApiController
{
    public function listComment(Request $request, $limit = 5, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $comment = Comment::whereHas('post', function($q) use ($request) {
            $q->where('slug', $request->post_slug);
        });

        $commentsCount = $comment->get()->count();
        $commentsCountParent = $comment->whereNull('parent_id')->get()->count();
        $listComment = fractal($comment->whereNull('parent_id')->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get(), new ListCommentTransformers);
        return $this->respondSuccessWithPaginationNested($listComment, $commentsCount, $commentsCountParent);
    }

    public function createComment(CreateCommentRequest $request)
    {
        $comment = new Comment;
        $comment->post_id = Post::where('slug', $request->post_slug)->first()->id;
        $comment->user_id = auth()->user()->id;
        $comment->slug = Str::lower(Str::random(5));
        $comment->content = $request->content;
        $comment->published = 1;
        if ($request->has('parent_id')) {
            $comment->parent_id = $request->parent_id;
        }
        $comment->save();
        $createComment = fractal(Comment::where('id', $comment->id)->firstOrFail(), new CreateCommentTransformers);
        return $this->respondSuccess($createComment);
    }

    public function updateComment(UpdateCommentRequest $request, $slug)
    {
        $comment = Comment::where('slug', $slug)
                            ->where('post_id', Post::where('slug', $request->post_slug)->first()->id)
                            ->where('user_id', auth()->user()->id)->firstOrFail();
        $comment->content = $request->content;
        $comment->save();
        return $this->respondSuccess($comment);
    }

    public function deleteComment(Request $request, $slug)
    {
        $comment = Comment::where('post_id', Post::where('slug', $request->post_slug)->first()->id)
                            ->where('user_id', auth()->user()->id)
                            ->where('slug', $slug)->first();
        $comment->delete();
        return $this->respondSuccess($comment);
    }

    public function singleComment(Request $request, $slug)
    {
        $comment = Comment::where('slug', $slug)
                            ->whereHas('post', function($q) use ($request) {
                                $q->where('slug', $request->post_slug)
                                ->where('user_id', User::where('user_name', $request->user_name)->firstOrFail()->id);
                            });
        $singleComment = fractal($comment->firstOrFail(), new SingleCommentTransformers);
        return $this->respondSuccess($singleComment);
    }

    public function editComment(Request $request, $slug)
    {
        $comment = Comment::where('slug', $slug)
                            ->where('user_id', auth()->user()->id)
                            ->whereHas('post', function($q) use ($request) {
                                $q->where('slug', $request->post_slug)
                                ->where('user_id', User::where('user_name', $request->user_name)->firstOrFail()->id);
                            });
        $editComment = fractal($comment->firstOrFail(), new SingleCommentTransformers);
        return $this->respondSuccess($editComment);
    }

    public function deleteCommentConfirm(Request $request, $slug)
    {
        $comment = Comment::where('slug', $slug)
                            ->where('user_id', auth()->user()->id)
                            ->whereHas('post', function($q) use ($request) {
                                $q->where('slug', $request->post_slug)
                                ->where('user_id', User::where('user_name', $request->user_name)->firstOrFail()->id);
                            });
        $deleteCommentConfirm = fractal($comment->firstOrFail(), new SingleCommentTransformers);
        return $this->respondSuccess($deleteCommentConfirm);
    }

    public function favoriteComment(Request $request)
    {
        $user = auth()->user();

        $commentFavorite = Comment::where('slug', $request->slug)->firstOrFail();

        $commentCheck = FavoriteComment::where('user_id', $user->id)->where('comment_id', $commentFavorite->id)->first();

        if(!$commentCheck) {
            $favorite = new FavoriteComment;
            $favorite->user_id = $user->id;
            $favorite->comment_id = $commentFavorite->id;
            $favorite->save();
            return $this->respondSuccess([
                'id' => $favorite->comment->id,
                'slug' => $favorite->comment->slug
            ]);
        } else {
            return $this->respondUnprocessableEntity('Comment favorited');
        }
    }

    public function unfavoriteComment(Request $request)
    {
        $user = auth()->user();

        $commentFavorite = Comment::where('slug', $request->slug)->firstOrFail();

        $favoriteCheck = FavoriteComment::where('user_id', $user->id)->where('comment_id', $commentFavorite->id)->first();

        if(!!$favoriteCheck) {
            $favoriteCheck->delete();
            return $this->respondSuccess([
                'id' => $favoriteCheck->comment->id,
                'slug' => $favoriteCheck->comment->slug
            ]);
        } else {
            return $this->respondUnprocessableEntity('Comment does not exist or not in the favoritelist');
        }
    }
}
