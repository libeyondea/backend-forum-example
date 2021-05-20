<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Transformers\ListCategory\ListCategoryTransformers;
use App\Transformers\SingleCategory\SingleCategoryTransformers;

class CategoryController extends Controller
{
    private $listCategoryTransformers;

    private $singleCategoryTransformers;

    public function __construct(ListCategoryTransformers $listCategoryTransformers, SingleCategoryTransformers $singleCategoryTransformers)
    {
        $this->listCategoryTransformers = $listCategoryTransformers;
        $this->singleCategoryTransformers = $singleCategoryTransformers;
    }

    public function listCategory(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $category = Category::orderBy('id', 'asc');
        $categoriesCount = $category->get()->count();
        $listCategory = fractal($category->skip($offset)->take($limit)->get(), $this->listCategoryTransformers);
        return response()->json([
            'success' => true,
            'data' => $listCategory,
            'meta' => [
                'categories_count' => $categoriesCount
            ]
        ], 200);
    }

    public function singleCategory($slug)
    {
        $category = Category::where('slug', $slug);
        $singleCategory = fractal($category->firstOrFail(), $this->singleCategoryTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleCategory
        ], 200);
    }
}
