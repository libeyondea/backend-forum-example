<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
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
        'auth_token',
        'phone_number',
        'address',
        'gender',
        'avatar',
        'role_id',
        'email_verified_at'
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

    public function isFollowing()
    {
        $user = auth('api')->user();
        if ($user) {
            return !!$this->following()->where('user_id', $user->id)->count();
        } else {
            return false;
        }
    }

    public function isVerified()
    {
        $user = auth()->user();
        return !!$user->hasVerifiedEmail();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

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

    public function FollowUser()
    {
    	return $this->hasMany('App\Models\FollowUser', 'user_id', 'id');
    }

    public function Following()
    {
    	return $this->hasMany('App\Models\FollowUser', 'following_id', 'id');
    }

    public function FollowTag()
    {
    	return $this->hasMany('App\Models\FollowTag', 'user_id', 'id');
    }
}
