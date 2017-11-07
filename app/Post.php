<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'image_url', 'image_extension', 'user_id'
    ];

    public function likes(){
        return $this->belongsToMany('App\User', 'liked_posts', 'post_id', 'liked_by');
    }

    public function favorites(){
        return $this->belongsToMany('App\User', 'favorited_posts', 'post_id', 'favorited_by');
    }
}
