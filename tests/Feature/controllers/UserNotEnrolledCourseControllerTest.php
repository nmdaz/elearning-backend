<?php

namespace Tests\Feature\controllers;

use App\User;
use App\Course;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserNotEnrolledCourseControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    private $author;
    private $user;

    public function setUp() :void
    {
        parent::setUp();

        $this->author = factory(User::class)->create();
        $this->author->authoredCourses()->saveMany(factory(Course::class, 3)->make());

        $this->user = factory(User::class)->create();
        $this->user->enrolledCourses()->attach($this->author->authoredCourses()->first());
        $this->user->authoredCourses()->saveMany(factory(Course::class, 3)->make());
        $this->user->refresh();

        Sanctum::actingAs($this->user);
    }

    public function test_get_all_courses_not_enrolled_by_user()
    {
        $this->withoutExceptionHandling();

        $response = $this->getJson("/api/users/{$this->user->id}/not-enrolled-courses");

        //check if all recieve course is not enrolled by user and not authored by user
        $courses = collect($response['courses']);
        $courses->each(function($course) {
            $course = collect($course);
            
            $this->user->enrolledCourses->each(function($enrolled) use ($course) {
                $this->assertNotEquals($enrolled->id, $course['id']);
            });

            $this->assertNotEquals($this->user->id, $course['author_id']);
        });
    }
}
