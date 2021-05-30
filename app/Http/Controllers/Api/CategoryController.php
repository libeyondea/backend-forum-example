<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Transformers\ListCategory\ListCategoryTransformers;
use App\Transformers\SingleCategory\SingleCategoryTransformers;

class CategoryController extends ApiController
{
    public function listCategory(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $category = Category::orderBy('id', 'asc');
        $categoriesCount = $category->get()->count();
        $listCategory = fractal($category->skip($offset)->take($limit)->get(), new ListCategoryTransformers);
        return $this->respondSuccessWithPagination($listCategory, $categoriesCount);
    }

    public function singleCategory($slug)
    {
        $category = Category::where('slug', $slug);
        $singleCategory = fractal($category->firstOrFail(), new SingleCategoryTransformers);
        return $this->respondSuccess($singleCategory);
    }
}
