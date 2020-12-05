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
   		$storageHelper = resolve(DriveStorageHelper::class);
   		$fileName = $this->cover_image;

   		$coverImageType;
   		$coverImage;
		
		$file = $storageHelper->getFileUsingFileName($fileName);
		$rawData = $storageHelper->getRawDataUsingFile($file);

		if (!$file || !$rawData) {
			$coverImageType = null;
			$coverImage = null;
		} else {
			$coverImageType = $file['mimetype'];
			$coverImage = base64_encode($rawData);
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
