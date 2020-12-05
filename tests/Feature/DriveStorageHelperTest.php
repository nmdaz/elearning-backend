<?php

namespace Tests\Feature;

use App\Includes\DriveStorageHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Exception\ConnectException;

class DriveStorageTest extends TestCase
{
    protected $storageHelper;

    public function setUp() :void 
    {
        parent::setUp();
        $this->storageHelper = resolve(DriveStorageHelper::class);
    }

    public function test_put_file()
    {
        $coverImage = UploadedFile::fake()->image('cover.jpeg');
        $fileName = $this->storageHelper->put($coverImage);

        $this->assertNotNull($fileName);

        return $fileName;
    }

    /**
    * @depends test_put_file
    */
    public function test_getRawDataUsingFileName($fileName)
    {
        $rawData = $this->storageHelper->getRawDataUsingFileName($fileName);
        $this->assertNotNull($rawData);
    }

    /**
    * @depends test_put_file
    */
    public function test_delete_file($fileName)
    {
        $deleted = $this->storageHelper->delete($fileName);
        $this->assertTrue($deleted);
    }


}
