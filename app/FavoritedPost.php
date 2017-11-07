<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoritedPost extends Model
{
    protected $fillable = [
        'favorited_by', 'post_id'
    ];
}
