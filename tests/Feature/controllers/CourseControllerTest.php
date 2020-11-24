<?php

namespace Tests\Feature\controllers;

use App\Course;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CourseControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function setUp() :void 
    {
        parent::setUp();

        $user = factory(User::class)->create();
        $user->authoredCourses()->saveMany(
            factory(Course::class, 5)->make()
        );
    }

    public function test_get_all_courses_get_status_code_200()
    {
        $this->getJson('/api/courses')
            ->assertOk()
            ->assertJsonStructure(['courses']);
    }

    public function test_get_one_course_return_status_code_200()
    {
        $courseId = Course::first()->id;
        
        $this->getJson("/api/courses/$courseId")
            ->assertOk()
            ->assertJsonStructure(['course']);
    }

    public function test_cant_create_course_as_guest()
    {
        $this->postJson('api/courses/')
            ->assertUnauthorized();
    }

    public function test_create_course_failed_course_validation()
    {
        $imageFile = UploadedFile::fake()->image('xxx.jpg');
        $rarFile = UploadedFile::fake()->create('compressed.rar', 1, 'application/x-rar-compressed');

        $data = [
            'name' => '',
            'description' => '',
            'cover_image' => $rarFile,
            'attachment' => $imageFile
        ];

        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        $this->postJson('api/courses', $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'description',
                    'cover_image',
                    'attachment'
                ]
            ]);
    }

    public function test_create_course_success()
    {
        $this->withoutExceptionHandling();

        $imageFile = UploadedFile::fake()->image('xxx.jpg');
        $rarFile = UploadedFile::fake()->create('compressed.rar', 1, 'application/x-rar-compressed');

        $data = [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'cover_image' => $imageFile,
            'attachment' => $rarFile
        ];

        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        Storage::fake('public');

        $this->postJson('api/courses', $data)
            ->assertSuccessful()
            ->assertJsonStructure([
                'success',
                'course_id'
            ]);

        Storage::disk('public')->assertExists('/covers/' . $imageFile->hashName());
        Storage::disk('public')->assertExists('/attachments/' . $rarFile->hashName());

        $this->assertDatabaseHas('courses', [
            'name' => $data['name'],
            'description' => $data['description'],
            'cover_image' => 'covers/' . $imageFile->hashName()
        ]);
    }
}
