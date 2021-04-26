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
        $category = Category::orderBy('id', 'asc');
        $categoriesCount = $category->get()->count();
        $listCategory = fractal($category->skip($offset)->take($limit)->get(), $this->categoryTransformers);
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
        $singleCategory = fractal($category->first(), $this->categoryTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleCategory
        ], 200);
    }
}
