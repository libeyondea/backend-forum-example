<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
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

    public function singleTag($slug)
    {
        $tag = Tag::where('slug', $slug);
        $singleTag = fractal($tag->first(), $this->singleTagTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleTag
        ], 200);
    }
}
