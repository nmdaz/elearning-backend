<?php

namespace Tests\Feature\controllers;

use App\Comment;
use App\Course;
use App\Lesson;
use App\Section;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp() :void {
        parent::setUp();

        $user = factory(User::class)->create();
        $user->authoredCourses()->save(factory(Course::class)->make());
        $user->authoredCourses->first()->sections()->save(factory(Section::class)->make());
        $user->authoredCourses->first()->sections->first()->lessons()->save(factory(Lesson::class)->make());

        factory(Comment::class)->create(['lesson_id' => Lesson::first()->id, 'user_id' => User::first()->id]);

        $this->user = $user;
        $this->withHeaders(['Accept' => 'application/json']);
    }

    public function test_guest_cant_like_dislike_unlike_comment()
    {
        $comment = Comment::first(); 

        $this->post("api/comments/$comment->id/like")
            ->assertUnauthorized();

        $this->post("api/comments/$comment->id/dislike")
            ->assertUnauthorized();

        $this->post("api/comments/$comment->id/unlike")
            ->assertUnauthorized();
    }

    public function test_user_can_like_comment()
    {
        $user = User::first();
        $comment = Comment::first(); 

        Sanctum::actingAs($user);

        $response = $this->post("api/comments/$comment->id/like")
             ->assertSuccessful();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id, 
            'comment_id' => $comment->id,
            'liked' => true
        ]);
    }

    public function test_user_cant_relike_comment()
    {
        $user = User::first();
        $comment = Comment::first(); 

        Sanctum::actingAs($user);

        $response = $this->post("api/comments/$comment->id/like")
             ->assertSuccessful();

        $response = $this->post("api/comments/$comment->id/like")
            ->assertSuccessful();

        $response = $this->post("api/comments/$comment->id/like")
            ->assertSuccessful();
    }

    public function test_user_can_dislike_comment()
    {
        $user = User::first();
        $comment = Comment::first(); 

        Sanctum::actingAs($user);

        $this->post("api/comments/$comment->id/dislike")
             ->assertSuccessful();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id, 
            'comment_id' => $comment->id,
            'liked' => false
        ]);
    }

    public function test_user_can_unlike_comment()
    {
        $user = User::first();
        $comment = Comment::first(); 

        Sanctum::actingAs($user);

        $this->post("api/comments/$comment->id/like")
             ->assertSuccessful();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id, 
            'comment_id' => $comment->id,
            'liked' => true
        ]);

        $this->post("api/comments/$comment->id/unlike")
             ->assertSuccessful();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id, 
            'comment_id' => $comment->id
        ]);
    }




}
