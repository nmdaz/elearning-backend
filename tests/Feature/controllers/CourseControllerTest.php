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

    private $user;

    public function setUp() :void 
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->user->authoredCourses()->saveMany(
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

    public function test_user_cant_edit_other_users_course() {        
        $course = $this->user->authoredCourses()->first();
        $user2 = factory(User::class)->create();
        Sanctum::actingAs($user2);
        $this->patchJson("api/courses/$course->id")->assertForbidden();
    }

    public function test_edit_course_return_validation_error() {
        $course = $this->user->authoredCourses()->first();
        Sanctum::actingAs($this->user);
        $this->patchJson("api/courses/$course->id")->assertStatus(422);
    }

    public function test_edit_course_cover_image() {
        Sanctum::actingAs($this->user);
        Storage::fake('public');

        $course = $this->user->authoredCourses()->first();
        $imageFile = UploadedFile::fake()->image('xxx.jpg');
        $data = [
            'cover_image' => $imageFile
        ];

        $this->patchJson("api/courses/$course->id", $data)
        ->assertJsonStructure(['success']);

        $data['cover_image'] = 'covers/'. $data['cover_image']->hashName();

        Storage::disk('public')->assertExists('/covers/'. $imageFile->hashName());

        $this->assertDatabaseHas('courses', $data);
    }

    public function test_edit_course() {
        Sanctum::actingAs($this->user);
        Storage::fake('public');

        $course = $this->user->authoredCourses()->first();
        $newName = $this->faker->sentence;
        $newDescription = $this->faker->paragraph;

        $imageFile = UploadedFile::fake()->image('xxx.jpg');
        $rarFile = UploadedFile::fake()->create('compressed.rar', 1, 'application/x-rar-compressed');

        $data = [
            'name' => $newName,
            'description' => $newDescription,
            'cover_image' => $imageFile,
            'attachment' => $rarFile
        ];

        $this->patchJson("api/courses/$course->id", $data)
        ->assertJsonStructure(['success']);

        $data['cover_image'] = 'covers/'. $data['cover_image']->hashName();
        $data['attachment'] = 'attachments/'. $data['attachment']->hashName();

        Storage::disk('public')->assertExists('/covers/'. $imageFile->hashName());
        Storage::disk('public')->assertExists('/attachments/'. $rarFile->hashName());

        $this->assertDatabaseHas('courses', $data);
    }

    public function test_download_attachment_but_course_has_no_attachment()
    {
        $course =  $this->user->authoredCourses()->first();
        $this->get("api/courses/$course->id/download-attachment")->assertNotFound()
            ->assertJsonStructure(['errors' => [ 'attachment' ]]);
    }

    public function test_download_attachment()
    {
        $rarFile = UploadedFile::fake()->create('compressed.rar', 1, 'application/x-rar-compressed');
        $course =  $this->user->authoredCourses()->first();
        $course->attachment = $rarFile;
        $course->save();
        
        $response = $this->get("api/courses/$course->id/download-attachment")->assertSuccessful();
    }
}
