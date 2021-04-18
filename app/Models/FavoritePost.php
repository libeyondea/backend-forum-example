<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoritePost extends Model
{
    protected $table = 'favorite_post';
    protected $primaryKey = ['user_id', 'post_id'];

    public function User()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function Post()
    {
    	return $this->belongsTo('App\Models\Post', 'post_id', 'id');
    }


}
