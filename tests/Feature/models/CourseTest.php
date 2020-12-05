<?php

namespace Tests\Feature\model;

use App\Course;
use App\Includes\DriveStorageHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// Todo: Only save cover_image and attachment when model is save

class CourseTest extends TestCase
{
    public function setUp() :void {
        parent::setUp();
    }
    
    public function test_course_cover_image()
    {
        $coverImage = UploadedFile::fake()->image('cover.jpeg');

        $course = new Course();
        $course->cover_image = $coverImage;

        Storage::cloud()->assertExists($course->cover_image);

        $rawData = resolve(DriveStorageHelper::class)->getRawDataUsingFileName($course->cover_image);

        $this->assertNotNull($rawData);
    }

    public function test_course_attachment()
    {
        $rarFile = UploadedFile::fake()->create('compressed.rar', 1, 'application/x-rar-compressed');

        $course = new Course();
        $course->attachment = $rarFile;

        Storage::cloud()->assertExists($course->attachment);

        return $course;
    }

    /**
    * @depends test_course_attachment
    */
    public function test_course_remove_attachment($course)
    {
        $course->removeAttachment();
        Storage::cloud()->assertMissing($course->attachment);
        $this->assertNull($course->attachment);
    }
}
