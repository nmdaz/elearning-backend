<?php

namespace Tests\Feature\model;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Repositories\UserRepository;

class UserTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp() :void {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_user_has_many_courses()
    {
    	$user = factory(User::class)->create();
    	$this->assertInstanceOf(Collection::class, $user->courses);
    }
}
