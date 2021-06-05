<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'id';

    public function isFavorited()
    {
        $user = auth('api')->user();
        if ($user) {
            return !!$this->favoritepost()->where('user_id', $user->id)->count();
        } else {
            return false;
        }
    }

    public function User()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function PostTag()
    {
    	return $this->hasMany('App\Models\PostTag', 'post_id', 'id');
    }

    public function Tag()
    {
        return $this->belongsToMany('App\Models\Tag', 'post_tag');
    }

    public function Category()
    {
    	return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function Comment()
    {
    	return $this->hasMany('App\Models\Comment', 'post_id', 'id');
    }

    public function FavoritePost()
    {
    	return $this->hasMany('App\Models\FavoritePost', 'post_id', 'id');
    }

    public function UserFavorite()
    {
        return $this->belongsToMany('App\Models\User', 'favorite_post');
    }
}
