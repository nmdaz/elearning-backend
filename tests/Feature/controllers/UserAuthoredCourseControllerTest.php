<?php

namespace Tests\Feature\controllers;

use App\User;
use App\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserAuthoredCourseControllerTest extends TestCase
{
	use RefreshDatabase;
	use WithFaker;

	private $user;

	public function setUp() :void
	{
		parent::setUp();

		$user = factory(User::class)->create();
		$user->authoredCourses()->saveMany(factory(Course::class, 4)->make());

		Sanctum::actingAs($user);
	}

    public function test_get_all_authored_course_by_user()
    {
    	$this->getJson("/api/users/$this->user->id/authored-courses")
    		->assertSuccessful();
    }
}
