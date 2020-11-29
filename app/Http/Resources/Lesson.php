<?php

namespace App\Http\Resources;

use App\Includes\YoutubeIdExtractor;
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

    private $extractor;

    public function __construct($resource)
    {
        $this->resource = $resource;
        $this->extractor = resolve('App\Includes\YoutubeIdExtractor');
    }
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'section_id' => $this->section->id,
            'name' => $this->name,
            'video_url' => $this->video_url,
            'video_id' => $this->extractor->extractId($this->video_url),
            'created_at' => $this->created_at,
            'updated_at' => $this->update_at,
            'comments' => new CommentCollection($this->comments()->latest()->get())
        ];
    }
}
