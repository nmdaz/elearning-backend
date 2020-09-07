<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Reply extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment_id' => $this->comment_id,
            'user' => $this->user,
            'body' => $this->body,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
