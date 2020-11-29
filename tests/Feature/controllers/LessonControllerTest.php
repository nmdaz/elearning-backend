<?php

namespace Tests\Feature\controllers;

use App\Course;
use App\Lesson;
use App\User;
use App\Section;
use App\Includes\YoutubeIdExtractor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class LessonControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    private $user;
    private $extractor;

    public function setUp() :void {
        parent::setUp();

        $user = factory(User::class)->create();
        $user->authoredCourses()->save(factory(Course::class)->make());
        $user->authoredCourses->first()->sections()->save(factory(Section::class)->make());
        $user->authoredCourses->first()->sections->first()->lessons()->save(factory(Lesson::class)->make());

        $this->user = $user;

        $this->extractor = new YoutubeIdExtractor();

    }

    public function test_retrieve_lesson()
    {        
        $user = $this->user;
        $course = $user->authoredCourses->first();
        $section = $course->sections->first();
        $lesson = $section->lessons->first();

        Sanctum::actingAs($user);

        $this->getJson("api/courses/$course->id/sections/$section->id/lessons/$lesson->id")
            ->assertSuccessful()
            ->assertJson([
                'lesson' => [
                    'name' => $lesson->name,
                    'video_url' => $lesson->video_url,
                    'video_id' => $this->extractor->extractId($lesson->video_url),
                    'section_id' => $section->id
                ]
            ]);
    }

    public function test_guest_cant_create_lesson()
    {
        $user = $this->user;
        $course = $user->authoredCourses->first();
        $section = $course->sections->first();

        $this->postJson("api/courses/$course->id/sections/$section->id/lessons",)
            ->assertUnauthorized();
    }

    public function test_create_lesson_recieve_validator_error()
    {
        $user = $this->user;
        $course = $user->authoredCourses->first();
        $section = $course->sections->first();

        $data = [];

        Sanctum::actingAs($user);

        $this->postJson("api/courses/$course->id/sections/$section->id/lessons", $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'video_url'
                ]
            ]);
    }

    public function test_create_lesson_successfully()
    {
        $user = $this->user;
        $course = $user->authoredCourses->first();
        $section = $course->sections->first();

        $data = ['name' => $this->faker->sentence, 'video_url' => 'http://youtu.be/dQw4w9WgXcQ'];

        Sanctum::actingAs($user);

        $this->postJson("api/courses/$course->id/sections/$section->id/lessons", $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('lessons', $data);
    }
}
