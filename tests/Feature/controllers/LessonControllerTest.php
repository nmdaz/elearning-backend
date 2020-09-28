<?php

namespace Tests\Feature\controllers;

use App\Course;
use App\Lesson;
use App\User;
use App\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_retrieve_lesson()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);
        $course->sections()->save(factory(Section::class)->make());
        $section = $course->sections->first();
        $section->lessons()->save(factory(Lesson::class)->make());
        $lesson = $section->lessons->first();

        $this->getJson("api/courses/$course->id/sections/$section->id/lessons/$lesson->id")
            ->assertSuccessful()
            ->assertJson([
                'lesson' => [
                    'name' => $lesson->name,
                    'video_url' => $lesson->video_url,
                    'section_id' => $section->id
                ]
            ]);
    }

    public function test_guest_cant_create_lesson()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);
        $course->sections()->save(factory(Section::class)->make());
        $section = $course->sections->first();

        $this->postJson("api/courses/$course->id/sections/$section->id/lessons",)
            ->assertUnauthorized();
    }

    public function test_create_lesson_recieve_validator_error()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);
        $course->sections()->save(factory(Section::class)->make());
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
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);
        $course->sections()->save(factory(Section::class)->make());
        $section = $course->sections->first();

        $data = ['name' => $this->faker->sentence, 'video_url' => $this->faker->url];

        Sanctum::actingAs($user);

        $this->postJson("api/courses/$course->id/sections/$section->id/lessons", $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('lessons', $data);
    }
}
