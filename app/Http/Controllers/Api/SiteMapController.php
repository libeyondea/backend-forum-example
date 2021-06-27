<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Transformers\SiteMap\PostTransformers;
use App\Transformers\SiteMap\CategoryTransformers;
use App\Transformers\SiteMap\TagTransformers;

class SiteMapController extends ApiController
{
    public function siteMap(Request $request)
    {
        if ($request->type == 'posts' || !$request->type) {
            $siteMap = new Post;
            $transformers = new PostTransformers;
        } else if ($request->type == 'categories') {
            $siteMap = new Category;
            $transformers = new CategoryTransformers;
        }  else if ($request->type == 'tags') {
            $siteMap = new Tag;
            $transformers = new TagTransformers;
        }

        $listSiteMap = fractal($siteMap->orderBy('updated_at', 'desc')->skip(0)->take(66666)->get(), $transformers);
        return $this->respondSuccess($listSiteMap);
    }
}
