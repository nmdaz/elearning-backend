<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
	protected $fillable = ['name'];
	
    public function course()
    {
    	return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
    	return $this->hasMany(Lesson::class);
    }
}
