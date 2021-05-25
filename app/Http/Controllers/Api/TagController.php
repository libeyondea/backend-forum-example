<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\PostTag;
use App\Models\FollowTag;
use Illuminate\Support\Facades\DB;
use App\Transformers\ListTag\ListTagTransformers;
use App\Transformers\SingleTag\SingleTagTransformers;

class TagController extends Controller
{
    private $listTagTransformers;

    private $singleTagTransformers;

    public function __construct(ListTagTransformers $listTagTransformers, SingleTagTransformers $singleTagTransformers)
    {
        $this->listTagTransformers = $listTagTransformers;
        $this->singleTagTransformers = $singleTagTransformers;
    }

    public function listTag(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $tag = new Tag;
        $tagsCount = $tag->get()->count();
        $listTag = fractal($tag->skip($offset)->take($limit)->get(), $this->listTagTransformers);
        return response()->json([
            'success' => true,
            'data' => $listTag,
            'meta' => [
                'tags_count' => $tagsCount
            ]
        ], 200);
    }

    public function listTagFollowed(Request $request, $limit = 20, $offset = 0)
    {
        $user = auth()->user();
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);

        $tag = Tag::whereHas('followtag', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $tagsCount = $tag->get()->count();
        $listTagFollowed = fractal($tag->skip($offset)->take($limit)->get(), $this->listTagTransformers);

        return response()->json([
            'success' => true,
            'data' => $listTagFollowed,
            'meta' => [
                'tags_count' => $tagsCount
            ]
        ], 200);
    }

    public function singleTag($slug)
    {
        $tag = Tag::where('slug', $slug);
        $singleTag = fractal($tag->firstOrFail(), $this->singleTagTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleTag
        ], 200);
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
            return response()->json([
                'success' => true,
                'data' =>  [
                    'id' => $follow->tag->id,
                    'slug' => $follow->tag->slug
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'errors' =>  'folllowed',
                'errors' => [
                    'type' => '',
                    'title' => 'Tag folllowed',
                    'status' => 400,
                    'detail' => 'Tag folllowed',
                    'instance' => ''
                ]
                ], 400);
        }
    }

    public function unFollowTag(Request $request)
    {
        $user = auth()->user();

        $tagFollowing = Tag::where('slug', $request->slug)->firstOrFail();

        $followCheck = FollowTag::where('user_id', $user->id)->where('tag_id', $tagFollowing->id)->firstOrFail();

        if(!!$followCheck) {
            $followCheck->delete();
            return response()->json([
                'success' => true,
                'data' =>  [
                    'id' => $followCheck->tag->id,
                    'slug' => $followCheck->tag->slug
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => 'Tag unFolllowed.',
                    'status' => 400,
                    'detail' => '',
                    'instance' => ''
                ]
            ], 400);
        }
    }
}
