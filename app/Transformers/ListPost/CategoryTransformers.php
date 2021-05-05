<?php

namespace App\Transformers\ListPost;

use League\Fractal\TransformerAbstract;
use App\Models\Category;

class CategoryTransformers extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'title' => $category->title,
            'slug' => $category->slug
        ];
    }
}
