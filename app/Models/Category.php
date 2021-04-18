<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';

    public function Post()
    {
    	return $this->hasMany('App\Models\Post', 'category_id', 'id');
    }

}
