<?php

namespace Tests\Feature\controllers;

use App\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void 
    {
        parent::setUp();
        $this->seed();
        $this->withoutExceptionHandling();
    }

    public function test_get_all_courses_get_status_code_200()
    {
        $response = $this->getJson('/api/courses');
        $response->assertOk();

        $this->assertNotNull($response['courses']);
    }

    public function test_get_one_course_return_status_code_200()
    {
        $courseId = Course::first()->id;
        $response = $this->getJson("/api/courses/$courseId");
        $response->assertOk();

        $this->assertNotNull($response['course']);
    }
}
