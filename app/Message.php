<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'id', 'sender_id', 'receiver_id', 'chat_id', 'body'
    ];

    public function sender()
    {
      return $this->belongsTo('App\User', 'id', 'sender_id');
    }

    public function receiver()
    {
      return $this->belongsTo('App\User', 'id', 'receiver_id');
    }

    public function chat(){
        $this->belongsTo('App\Chat');
    }
}
