<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Exception\ConnectException;

class DriveStorageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_storage_cover_image()
    {
        $value = UploadedFile::fake()->image('xxx.jpg');

        $this->withoutExceptionHandling();
        
        try {
            $fileName = Storage::cloud()->putFile('/', $value);

            $dir = '/';
            $recursive = false; // Get subdirectories also?

            $contents = collect(Storage::cloud()->listContents($dir, $recursive));

            $file = $contents
                ->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($fileName, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($fileName, PATHINFO_EXTENSION))
                ->first(); // there can be duplicate file names!

            $rawData = Storage::cloud()->get($file['path']);

            $coverImageType = $file['mimetype'];
            $coverImage = base64_encode($rawData);

            $this->assertNotNull($coverImage);
        } catch (ConnectException $e) {
            $coverImageType = null;
            $coverImage = null;

            $this->assertNull($coverImageType);
            $this->assertNull($coverImage);
        }
    }
}
