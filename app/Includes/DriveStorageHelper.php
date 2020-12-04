<?php

namespace App\Includes;

use Illuminate\Support\Facades\Storage;

class DriveStorageHelper {
	public function getRawDataFromFileName($fileName)
	{
		$dir = '/';
		$recursive = false;
		$contents = collect(Storage::cloud()->listContents($dir, $recursive));
		
		$file = $contents
		    ->where('type', '=', 'file')
		    ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
		    ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
		    ->first(); // there can be duplicate file names!

		$rawData = Storage::cloud()->get($file['path']);

		return $rawData;
	}
}