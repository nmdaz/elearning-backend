<?php

namespace Tests\Feature\controllers;

use App\Course;
use App\User;
use App\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SectionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_guest_cant_create_section()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);

        $this->postJson("/api/courses/$course->id/sections")
            ->assertUnauthorized();
    }

    public function test_create_section_with_failed_validation()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/courses/$course->id/sections")
            ->assertStatus(422)
            ->assertJsonStructure(['errors' => [
                'name'
            ]]);
    }

    public function test_create_section_successfully()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);

        $data = ['name' => $this->faker->sentence];

        Sanctum::actingAs($user);

        $this->postJson("/api/courses/$course->id/sections", $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'section_id'
            ]);
    }

    public function test_get_one_section_of_course()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);
        $course->sections()->save(factory(Section::class)->make());
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
        $sectionCount = 5;

        $user = factory(User::class)->create();
        $course = factory(Course::class)->create([
            'author' => $user->id
        ]);
        $course->sections()->saveMany(factory(Section::class, $sectionCount)->make());

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
