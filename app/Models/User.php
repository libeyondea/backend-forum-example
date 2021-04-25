<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facebook_id',
        'google_id',
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'auth_token',
        'phone_number',
        'address',
        'gender',
        'avatar',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Post()
    {
    	return $this->hasMany('App\Models\Post', 'user_id', 'id');
    }

    public function Comment()
    {
    	return $this->hasMany('App\Models\Comment', 'user_id', 'id');
    }

    public function Role()
    {
    	return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function Follow()
    {
    	return $this->hasMany('App\Models\Follow', 'user_id', 'id');
    }

    public function Following()
    {
    	return $this->hasMany('App\Models\Follow', 'following_id', 'id');
    }
}
