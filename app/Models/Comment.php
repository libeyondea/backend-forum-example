<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    protected $primaryKey = 'id';

    public function isFavorited()
    {
        $user = auth('api')->user();
        if ($user) {
            return !!$this->favoritecomment()->where('user_id', $user->id)->count();
        } else {
            return false;
        }
    }

    public function Post()
    {
    	return $this->belongsTo('App\Models\Post', 'post_id', 'id');
    }

    public function User()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function ChildrenComment()
    {
    	return $this->hasMany('App\Models\Comment', 'parent_id', 'id');
    }

    public function ParentComment()
    {
    	return $this->belongsTo('App\Models\Comment', 'parent_id', 'id');
    }

    public function FavoriteComment()
    {
    	return $this->hasMany('App\Models\FavoriteComment', 'comment_id', 'id');
    }
}
