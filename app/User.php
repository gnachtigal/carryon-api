<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    AuthenticatableUserContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'voluntary'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function receivedMessages()
    {
        return $this->hasMany('App\Message', 'receiver_id', 'id');
    }

    public function sentMessages()
    {
        return $this->hasMany('App\Message', 'sender_id', 'id');
    }

    public function posts(){
        return $this->hasMany('App\Post');
    }

    public function likedPosts(){
        return $this->belongsToMany('App\Post', 'liked_posts', 'liked_by', 'post_id');
    }

    public function favoritedPosts(){
        return $this->belongsToMany('App\Post', 'favorited_posts', 'favorited_by', 'post_id');
    }

    public function myRatings(){
        if($this->voluntary){
            return $this->belongsToMany('App\User', 'users_ratings', 'user_id', 'rated_by');
        }else{
            return $this->belongsToMany('App\User', 'users_ratings', 'rated_by', 'user_id');
        }
    }

    public function chats(){
        if($this->voluntary){
            return $this->belongsToMany('App\Chat', 'user_chats', 'voluntary_id', 'chat_id');
        }else{
            return $this->belongsToMany('App\Chat', 'user_chats', 'user_id', 'chat_id');
        }
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Eloquent model method
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
             'user' => [
                'id' => $this->id,
                'email' => $this->email,
                'password' => $this->password,
                'voluntary' => $this->voluntary,
             ]
        ];
    }
}
