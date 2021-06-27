<?php

namespace App\Transformers\SiteMap;

use League\Fractal\TransformerAbstract;
use App\Models\Category;

class CategoryTransformers extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'slug' => $category->slug,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ];
    }
}
