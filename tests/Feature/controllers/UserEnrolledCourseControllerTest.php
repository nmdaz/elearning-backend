<?php

namespace Tests\Feature\controllers;

use App\User;
use App\Course;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class UserEnrolledCoursesControllerTest extends TestCase
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
        $this->user->refresh();

        Sanctum::actingAs($this->user);
    }

    public function test_get_all_enrolled_course_by_user()
    {
        $this->getJson("/api/users/$this->user->id/enrolled-courses")
            ->assertOk()
            ->assertJsonStructure(['courses']);
    }

    public function test_enroll_user_to_course()
    {        
        $course = $this->author->authoredCourses()->get()[1];

        $this->postJson("/api/users/{$this->user->id}/enrolled-courses/{$course->id}")
            ->assertStatus(201);

        $this->assertDatabaseHas('course_user', ['course_id' => $course->id, 'user_id' => $this->user->id]);
    }

    public function test_user_cant_enroll_again_on_enrolled_course()
    {
        $this->withoutExceptionHandling();

        $course = $this->user->enrolledCourses->first();

        $this->postJson("/api/users/{$this->user->id}/enrolled-courses/{$course->id}")
            ->assertSuccessful();

        $result = DB::select('select * from course_user where user_id = ? and course_id = ?', [$this->user->id, $course->id]);

        $this->assertLessThan(2, count($result));
    }

    public function test_unenroll_user_to_course()
    {
        $course = $this->user->enrolledCourses->first();

        $this->deleteJson("/api/users/{$this->user->id}/enrolled-courses/{$course->id}")
            ->assertOk();

        $this->assertDatabaseMissing('course_user', ['course_id' => $course->id, 'user_id' => $this->user->id]);
    }
}
