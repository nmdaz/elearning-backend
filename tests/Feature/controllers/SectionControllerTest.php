<?php

namespace Tests\Feature\controllers;

use App\Course;
use App\Lesson;
use App\User;
use App\Section;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SectionControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    private $user;

    public function setUp() :void {
        parent::setUp();

        $user = factory(User::class)->create();
        $user->authoredCourses()->save(factory(Course::class)->make());
        $user->authoredCourses->first()->sections()->saveMany(factory(Section::class, 5)->make());
        $user->authoredCourses->first()->sections->first()->lessons()->save(factory(Lesson::class)->make());

        $this->user = $user;
    }

    public function test_guest_cant_create_section()
    {
        $course = Course::first();

        $this->postJson("/api/courses/$course->id/sections")
            ->assertUnauthorized();
    }

    public function test_user_cant_create_section_of_unauthored_course()
    {
        $user2 = factory(User::class)->create();
        $course = Course::first();

        Sanctum::actingAs($user2);

        $this->postJson("/api/courses/$course->id/sections")
            ->assertForbidden();
    }

    public function test_create_section_with_failed_validation()
    {
        $this->withoutExceptionHandling();

        $course = $this->user->authoredCourses->first();

        Sanctum::actingAs($this->user);

        $this->postJson("/api/courses/$course->id/sections")
            ->assertStatus(422)
            ->assertJsonStructure(['errors' => [
                'name'
            ]]);
    }

    public function test_create_section_successfully()
    {
        $course = $this->user->authoredCourses->first();

        $data = ['name' => $this->faker->sentence];

        Sanctum::actingAs($this->user);

        $this->postJson("/api/courses/$course->id/sections", $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'section_id'
            ]);
    }

    public function test_get_one_section_of_course()
    {
        $course = $this->user->authoredCourses->first();
        $section = $course->sections->first();

        $this->getJson("/api/courses/$course->id/sections/$section->id")
            ->assertSuccessful()
            ->assertJson([
                'section' => [
                    'course_id' => $course->id,
                    'name' => $section->name
                ]
            ]);
    }

    public function test_get_all_section_of_course()
    {
        $course = $this->user->authoredCourses->first();

        $this->getJson("/api/courses/$course->id/sections")
            ->assertSuccessful()
            ->assertJsonStructure([
                'sections' => [
                    [
                        'course_id',
                        'name'
                    ]
                ]
            ]);
    }
}
