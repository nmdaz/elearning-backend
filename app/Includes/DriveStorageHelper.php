<?php

namespace App\Includes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DriveStorageHelper {

	protected $dir = '/';
	protected $recursive = false;

	protected function getContents()
	{
		try {
			$contents = collect(
				Storage::cloud()->listContents($this->dir, $this->recursive)
			);
			return $contents;
		} catch (\Exception $e) {
			return null;
		}
	}

	protected function getFileFromContents($contents, $fileName)
	{
		try {
			$file = $contents
			    ->where('type', '=', 'file')
			    ->where('filename', '=', pathinfo($fileName, PATHINFO_FILENAME))
			    ->where('extension', '=', pathinfo($fileName, PATHINFO_EXTENSION))
			    ->first(); // there can be duplicate file names!

			 if (!$file) {
			 	return null;
			 }

			 return $file;

		} catch (\Exception $e) {
			return null;
		}
	}

	public function getRawDataUsingFile($file)
	{
		try {
			$rawData = Storage::cloud()->get($file['path']);

			if (!$rawData) {
				return null;
			}

			return $rawData;
		} catch (\Exception $e) {
			return null;
		}
	}

	public function getFileUsingFileName($fileName)
	{
		$contents = $this->getContents();

		if (!$contents) {
			return null;
		}

		$file = $this->getFileFromContents($contents, $fileName);

		if (!$file) {
			return null;
		}

		return $file;
	}


	public function getRawDataUsingFileName($fileName)
	{
		$file = $this->getFileUsingFileName($fileName);

		if (!$file) {
			return null;
		}

		$rawData = $this->getRawDataUsingFile($file);

		if (!$rawData) {
			return null;
		}

		return $rawData;
	}

	public function put(UploadedFile $file)
	{
		try {
			$path = Storage::cloud()->putFile('/', $file);
			return $path;
		} catch (\Exception $e) {
			return null;
		}
	}

	public function delete($fileName)
	{		
		$file = $this->getFileUsingFileName($fileName);

		if (!$file) {
			return false;
		}

		try {
			Storage::cloud()->delete($file['path']);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}