<?php

namespace App\Transformers\ListCategory;

use League\Fractal\TransformerAbstract;
use App\Models\Category;

class CategoryTransformers extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'title' => $category->title,
            'slug' => $category->slug,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'total_posts' =>$category->post->count()
        ];
    }
}
