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
