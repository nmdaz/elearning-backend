<?php

namespace Tests\Feature\controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\User;

class LessonCommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp() :void {
        parent::setUp();
        $this->seed();
        $this->withoutExceptionHandling();
    }

    public function test_add_comment_to_lesson_return_201_status_code()
    {
        $user = User::first();
        $course = $user->courses->first();
        $section = $course->sections->first();
        $lesson = $section->first();

        Sanctum::actingAs($user);

        $comment = $this->faker->paragraph;
        $url = "/api/lessons/$lesson->id/comments";

        $this->postJson($url, ['body' => $comment])
            ->assertStatus(201);

        $this->assertDatabaseHas('comments', ['body' => $comment]);
    }
}
