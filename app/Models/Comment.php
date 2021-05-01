<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'parent_id',
        'content',
        'published',
        'published_at'
    ];

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

    //public function AllChildrenComment()
    //{
    //    return $this->ChildrenComment()->with('ChildrenComment');
    //}
}
