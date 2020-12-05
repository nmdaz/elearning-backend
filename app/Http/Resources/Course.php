<?php

namespace App\Http\Resources;

use App\Includes\DriveStorageHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

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
		   'author_id' => $this->author->id,
		   'name' => $this->name,
		   'description' => $this->description,
		   'attachment_url' => $this->attachment,
		   'created_at' => $this->created_at,
		   'updated_at' => $this->updated_at,
		   'sections' => $this->when(static::$includeRelations, new SectionCollection($this->sections)),
		   'cover_image_mime_type' => $coverImageType,
		   'cover_image' => $coverImage
	   ];
   }
}
