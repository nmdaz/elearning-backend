<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Course extends JsonResource
{
   public static $wrap = 'course';
   public static $includeRelations = true;
   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
   public function toArray($request)
   {
       $coverImageType = Storage::disk('public')->getMimetype($this->cover_image);
       $coverImage = base64_encode(Storage::disk('public')->get($this->cover_image));

       return [
           'id' => $this->id,
           'author_id' => $this->author->id,
           'name' => $this->name,
           'cover_image' => $coverImage,
           'cover_image_mime_type' => $coverImageType,
           'description' => $this->description,
           'attachment_url' => $this->attachment,
           'created_at' => $this->created_at,
           'updated_at' => $this->updated_at,
           'sections' => $this->when(static::$includeRelations, new SectionCollection($this->sections))
       ];
   }
}
