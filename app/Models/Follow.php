<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follow';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'follower_id'
    ];

    public function User()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function Following()
    {
    	return $this->belongsTo('App\Models\User', 'following_id', 'id');
    }

}
