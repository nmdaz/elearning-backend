<?php

namespace Tests\Feature\model;

use App\Comment;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void {
        parent::setUp();
        //Schema::disableForeignKeyConstraints();
    }

    public function test_likeBy() {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create(['lesson_id' => 1, 'user_id' => 1]);
        $comment->likeBy($user);

        $data = [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
            'liked' => true
        ];

        $this->assertDatabaseHas('likes', $data);
        $this->assertNotEquals(0, $comment->likes()->where($data)->get()->count());
    }

    public function test_dislikeBy() {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create(['lesson_id' => 1, 'user_id' => 1]);
        $comment->dislikeBy($user);

        $data = [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
            'liked' => false
        ];

        $this->assertDatabaseHas('likes', $data);
        $this->assertNotEquals(0, $comment->likes()->where($data)->get()->count());
    }

    public function test_unlikeBy() {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create(['lesson_id' => 1, 'user_id' => 1]);
        $comment->likeBy($user);
        $comment->unlikeBy($user);

        $data = [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
            'liked' => false
        ];

        $this->assertDatabaseMissing('likes', $data);
        $this->assertEquals(0, $comment->likes()->where($data)->get()->count());
    }
}
