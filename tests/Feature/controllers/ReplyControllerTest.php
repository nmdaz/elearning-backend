<?php

namespace Tests\Feature\controllers;

use App\Comment;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReplyControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp() :void
    {
        parent::setUp();
        $this->withHeaders(['Accept' => 'application/json']);
    }

    public function test_guest_cant_reply_on_comment()
    {
        Schema::disableForeignKeyConstraints();

        $comment = factory(Comment::class)->create(['lesson_id' => 1, 'user_id' => 1]);

        Schema::enableForeignKeyConstraints();

        $this->post("/api/comments/$comment->id/replies")
            ->assertUnauthorized();
    }

    public function test_user_post_empty_reply()
    {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create(['lesson_id' => 1, 'user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->post("/api/comments/$comment->id/replies")
            ->assertStatus(422)
            ->assertJson([
                'errors' => [ 'body'=> [] ]
            ]);
    }

    public function test_user_reply_on_comment()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create(['lesson_id' => 1, 'user_id' => $user->id]);

        Sanctum::actingAs($user);

        $data = [
            'user_id' => $user->id,
            'comment_id' => $comment->id,
            'body' => $this->faker->paragraph 
        ];

        $this->post("/api/comments/$comment->id/replies", ['body' => $data['body']])
            ->assertSuccessful();

        $this->assertDatabaseHas('replies', $data);
    }
}
