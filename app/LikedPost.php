<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikedPost extends Model
{
    protected $fillable = [
        'liked_by', 'post_id'
    ];
}
