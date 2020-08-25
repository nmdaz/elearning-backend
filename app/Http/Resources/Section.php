<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Section extends JsonResource
{
    public static $wrap = 'section';
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
            'course_id' => $this->course_id,
            'name' => $this->name,
            'description' => $this->description,
            'lessons' => new LessonCollection($this->lessons),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
