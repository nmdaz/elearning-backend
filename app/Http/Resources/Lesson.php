<?php

namespace App\Http\Resources;

use App\Http\Resources\CommentCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Lesson extends JsonResource
{
    public static $wrap = 'lesson';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'video' => $this->video,
            'created_at' => $this->created_at,
            'updated_at' => $this->update_at,
            'comments' => new CommentCollection($this->comments()->latest()->get())
        ];
    }
}
