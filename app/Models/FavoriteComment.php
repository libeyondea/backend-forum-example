<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteComment extends Model
{
    protected $table = 'favorite_comment';
    protected $primaryKey = 'id';

    public function User()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function Comment()
    {
    	return $this->belongsTo('App\Models\Comment', 'comment_id', 'id');
    }
}
