<?php

namespace Tests\Feature\controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\User;
use App\Course;
use App\Section;
use App\Lesson;

class CommentControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    private $user;

    public function setUp() :void {
        parent::setUp();

        $user = factory(User::class)->create();
        $user->authoredCourses()->save(factory(Course::class)->make());
        $user->authoredCourses->first()->sections()->save(factory(Section::class)->make());
        $user->authoredCourses->first()->sections->first()->lessons()->save(factory(Lesson::class)->make());

        Sanctum::actingAs($user);

        $this->user = $user;
    }

    public function test_add_comment_to_lesson_return_201_status_code()
    {        
        $lesson = $this->user->authoredCourses->first()
            ->sections->first()
            ->lessons->first();

        $comment = $this->faker->paragraph;

        $this->postJson("/api/lessons/$lesson->id/comments", ['body' => $comment])
            ->assertStatus(201);

        $this->assertDatabaseHas('comments', ['body' => $comment]);
    }
}
