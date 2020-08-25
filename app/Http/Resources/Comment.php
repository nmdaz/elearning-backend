<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'created_at' => $this->create_at,
            'updated_at' => $this->updated_at,
            'user_name' => $this->user->name
        ];
    }
}
