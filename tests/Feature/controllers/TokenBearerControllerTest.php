<?php

namespace Tests\Feature\controllers;

use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TokenBearerControllerTest extends TestCase
{
	use RefreshDatabase;

	public function setUp() :void {
		parent::setUp();

        $this->withoutExceptionHandling();
	}

    public function test_guest_access_tokenbearer_route_return_AuthenticationException()
    {
        $this->expectException(AuthenticationException::class);

        $response = $this->get('/api/tokenbearer');
    }

    public function test_with_bearer_token_return_status_code_ok()
    {
    	Sanctum::actingAs(factory(User::class)->create());

        $response = $this->get('/api/tokenbearer')
            ->assertOk();
    }
}
