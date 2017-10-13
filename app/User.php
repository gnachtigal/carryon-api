<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
        return $this->belongsToMany('App\Chat');
    }
}
