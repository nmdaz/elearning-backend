<?php

namespace App;

use App\Comment;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'comment_id', 'liked'];

    public function comment()
    {
    	return $this->belongsTo(Comment::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
