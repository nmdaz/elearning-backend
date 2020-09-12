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

	}

    public function test_guest_cant_access_token_bearer_route()
    {
        $this->getJson('/api/me')->dump()
            ->assertUnauthorized();
    }

    public function test_with_bearer_token_return_status_code_ok()
    {
    	Sanctum::actingAs(factory(User::class)->create());

        $response = $this->getJson('/api/me')
            ->assertOk()
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email'
                ]
            ]);
    }
}
