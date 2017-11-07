<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersRating extends Model
{
    protected $fillable = [
        'rating', 'rated_by', 'user_id'
    ];
}
