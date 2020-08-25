<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
	
	protected $guarded = [];

    public function section()
    {
    	return $this->belongsTo(Section::class);
    }

    public function comments()
    {
    	return $this->hasMany(Comment::class);
    }
}
