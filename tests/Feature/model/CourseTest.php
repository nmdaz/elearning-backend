<?php

namespace Tests\Feature\model;

use App\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourseTest extends TestCase
{
    public function setUp() :void {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_course_author()
    {
        
    }
    
    public function test_course_cover_image_path_exists()
    {
        $coverImage = UploadedFile::fake()->image('cover.jpeg');

        $course = new Course();
        $course->cover_image = $coverImage;

        Storage::disk('public')->assertExists($course->cover_image);
        Storage::disk('public')->assertExists('/covers/' . $coverImage->hashName());
    }

   
}
