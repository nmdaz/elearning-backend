<?php

namespace App;

use App\Like;
use App\User;
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

    public function likes()
    {
    	return $this->hasMany(Like::class);
    }

    public function likeBy(User $user)
    {
        $this->likes()->updateOrCreate(
            ['user_id' => $user->id],
            ['liked' => true]
        );
    }

    public function dislikeBy(User $user)
    {
        $this->likes()->updateOrCreate(
            ['user_id' => $user->id],
            ['liked' => false]
        );
    }

    public function unlikeBy(User $user)
    {
        $this->likes()->where('user_id', $user->id)->delete();
    }

}
