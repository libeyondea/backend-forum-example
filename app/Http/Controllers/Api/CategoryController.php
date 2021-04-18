<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Transformers\CategoryTransformers;

class CategoryController extends Controller
{
    private $category;

    private $categoryTransformers;

    public function __construct(Category $category, CategoryTransformers $categoryTransformers)
    {
        $this->category = $category;
        $this->categoryTransformers = $categoryTransformers;
    }

    public function listCategory(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $category = $this->category;
        $listCategory = fractal($category->skip($offset)->take($limit)->get(), $this->categoryTransformers);
        $categoriesCount = $category->get()->count();
        return response()->json([
            'success' => true,
            'data' => $listCategory,
            'meta' => [
                'posts_count' => $categoriesCount
            ]
        ], 200);
    }

    public function singleCategory($slug)
    {
        $category = $this->category->where('slug', $slug);
        $singleCategory = fractal($category->first(), $this->categoryTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleCategory
        ], 200);
    }
}
