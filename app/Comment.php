<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
	protected $guarded = [];

    public function lesson()
    {
    	return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
