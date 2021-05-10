<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowTag extends Model
{
    protected $table = 'follow_tag';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'tag_id'
    ];

    public function User()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function Tag()
    {
    	return $this->belongsTo('App\Models\Tag', 'tag_id', 'id');
    }

}
