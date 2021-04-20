<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Transformers\TagTransformers;

class TagController extends Controller
{
    private $tag;

    private $tagTransformers;

    public function __construct(Tag $tag, TagTransformers $tagTransformers)
    {
        $this->tag = $tag;
        $this->tagTransformers = $tagTransformers;
    }

    public function listTag(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $tag = $this->tag;
        $tagsCount = $tag->get()->count();
        $listTag = fractal($tag->skip($offset)->take($limit)->get(), $this->tagTransformers);
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
        $tag = $this->tag->where('slug', $slug);
        $singleTag = fractal($tag->first(), $this->tagTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleTag
        ], 200);
    }
}
