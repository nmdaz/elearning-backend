<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class CoursePreview extends JsonResource
{
   public static $wrap = 'course';
   /**
	* Transform the resource into an array.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return array
	*/
   public function toArray($request)
   {
		if (App::environment('production')) {
			$filename = $this->cover_image;

			$dir = '/';
			$recursive = false; // Get subdirectories also?
			$contents = collect(Storage::cloud()->listContents($dir, $recursive));

			$file = $contents
				->where('type', '=', 'file')
				->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
				->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
				->first(); // there can be duplicate file names!

			$rawData = Storage::cloud()->get($file['path']);

			$coverImageType = $file['mimetype'];
			$coverImage = base64_encode($rawData);
		 } else {
			$coverImageType = Storage::disk('public')->getMimetype($this->cover_image);
			$coverImage = base64_encode(Storage::disk('public')->get($this->cover_image));
		 }

	   return [
		   'id' => $this->id,
		   'author' => $this->author->id,
		   'name' => $this->name,
		   'cover_image' => $coverImage,
		   'cover_image_mime_type' => $coverImageType,
		   'description' => $this->description,
		   'created_at' => $this->created_at,
		   'updated_at' => $this->updated_at
	   ];
   }
}
