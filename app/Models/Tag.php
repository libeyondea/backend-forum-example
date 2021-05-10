<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    protected $primaryKey = 'id';

    public function isFollowing()
    {
        $user = auth('api')->user();
        if ($user) {
            return !!$this->followtag()->where('user_id', $user->id)->count();
        } else {
            return false;
        }

    }

    public function PostTag()
    {
    	return $this->hasMany('App\Models\PostTag', 'tag_id', 'id');
    }

    public function Post()
    {
        return $this->belongsToMany('App\Models\Post', 'post_tag');
    }

    public function FollowTag()
    {
    	return $this->hasMany('App\Models\FollowTag', 'tag_id', 'id');
    }
}
