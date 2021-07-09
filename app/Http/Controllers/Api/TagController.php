<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\PostTag;
use App\Models\FollowTag;
use App\Transformers\ListTag\TagTransformers as ListTagTransformers;
use App\Transformers\ListTagWithPost\TagWithPostTransformers as ListTagWithPostTransformers;
use App\Transformers\SingleTag\TagTransformers as SingleTagTransformers;

class TagController extends ApiController
{
    public function listTag(Request $request)
    {
        $limit = $request->get('limit', 20);
        $offset = $request->get('offset', 0);

        $tag = Tag::withCount('post')->orderBy('post_count', 'desc')->orderBy('created_at', 'desc');

        $tagsCount = $tag->get()->count();
        $listTag = fractal($tag->skip($offset)->take($limit)->get(), new ListTagTransformers);
        return $this->respondSuccessWithPagination($listTag, $tagsCount);
    }

    public function listTagWithPost(Request $request)
    {
        $limit = $request->get('limit', 5);
        $offset = $request->get('offset', 0);

        $tag = Tag::withCount('post')->orderBy('post_count', 'desc')->orderBy('created_at', 'desc');

        $listTag = fractal($tag->skip($offset)->take($limit)->get(), new ListTagWithPostTransformers);
        return $this->respondSuccess($listTag);
    }

    public function listTagFollowed(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 20);
        $offset = $request->get('offset', 0);

        $tag = Tag::whereHas('followtag', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $tagsCount = $tag->get()->count();
        $listTagFollowed = fractal($tag->skip($offset)->take($limit)->get(), new ListTagTransformers);
        return $this->respondSuccessWithPagination($listTagFollowed, $tagsCount);
    }

    public function singleTag($slug)
    {
        $tag = Tag::where('slug', $slug);
        $singleTag = fractal($tag->firstOrFail(), new SingleTagTransformers);
        return $this->respondSuccess($singleTag);
    }

    public function followTag(Request $request)
    {
        $user = auth()->user();

        $tagFollowing = Tag::where('slug', $request->slug)->firstOrFail();

        $followCheck = FollowTag::where('user_id', $user->id)->where('tag_id', $tagFollowing->id)->first();

        if(!$followCheck) {
            $follow = new FollowTag;
            $follow->user_id = $user->id;
            $follow->tag_id = $tagFollowing->id;
            $follow->save();
            return $this->respondSuccess([
                'id' => $follow->tag->id,
                'slug' => $follow->tag->slug
            ]);
        } else {
            return $this->respondUnprocessableEntity('Tag folllowed');
        }
    }

    public function unFollowTag(Request $request)
    {
        $user = auth()->user();

        $tagFollowing = Tag::where('slug', $request->slug)->firstOrFail();

        $followCheck = FollowTag::where('user_id', $user->id)->where('tag_id', $tagFollowing->id)->first();

        if(!!$followCheck) {
            $followCheck->delete();
            return $this->respondSuccess([
                'id' => $followCheck->tag->id,
                'slug' => $followCheck->tag->slug
            ]);
        } else {
            return $this->respondUnprocessableEntity('Tag does not exist or not in the followlist');
        }
    }
}
