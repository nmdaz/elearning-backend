<?php

namespace App\Http\Resources;

use App\Http\Resources\LikeCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Comment extends JsonResource
{
    public static $wrap = "comment";

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lesson_id' => $this->lesson_id,
            'user_id' => $this->user_id,
            'body' => $this->body,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at,
            'user_name' => $this->user->name,
            'likes' => new LikeCollection($this->likes()->where('liked', true)->get()),
            'dislikes' => new LikeCollection($this->likes()->where('liked', false)->get())
        ];
    }
}
