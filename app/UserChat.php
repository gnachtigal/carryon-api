<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserChat extends Model
{
    protected $fillable = [
        'user_id', 'voluntary_id', 'chat_id'
    ];
}
