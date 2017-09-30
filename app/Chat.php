<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'id', 'title'
    ];

    public function messages(){
        return $this->hasMany('App\Message');
    }

    public function members(){
        return $this->hasMany('App\User');
    }
}
