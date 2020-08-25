<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private $registerInputs = [
        'name' => 'First Last',
        'email' => 'myemail@mailer.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ];

    public function setUp() :void {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_login_with_invalid_credentials_return_422_status_code()
    {
        $this->postJson('/api/auth/login')
            ->assertStatus(422);
    }

    public function test_login_with_invalid_credentials_return_json_error()
    {
        $this->postJson('/api/auth/login')
            ->assertJson([
                'error' => 'Wrong username or password'
            ]);
    }

    public function test_login_with_valid_credentials_return_auth_token_and_user_info()
    {
        $credentials = [
            'email' => 'email01@gmail.com',
            'password' => 'password01'
        ];

        $user = factory(User::class)->create($credentials);

        $this->postJson('/api/auth/login', $credentials)
            ->assertJsonStructure([
                'token',
                'user'
            ])
            ->assertJson([
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
    }

    public function test_register_with_invalid_data_return_422_status_code()
    {
        $response = $this->postJson('/api/auth/register');
        $response->assertStatus(422);
    }

    public function test_register_with_invalid_data_return_json_validation_error()
    {
        $response = $this->postJson('/api/auth/register');

        $response->assertJsonStructure([
            'errors' => [
                'name',
                'email',
                'password'
            ]
        ]);
    }

    public function test_register_with_taken_email_return_json_validation_error()
    {
        $data = $this->registerInputs;

        $this->postJson('/api/register', $data);

        $response = $this->postJson('/api/register', $data);

        $response->assertJsonStructure([
            'errors' => [
                'email'
            ]
        ]);
    }

    public function test_register_with_valid_data_return_201()
    {
        $data = $this->registerInputs;

        $this->postJson('/api/register', $this->registerInputs)
            ->assertStatus(201);
    }

    public function test_register_with_valid_data_return_json_user_and_token()
    {
        $data = $this->registerInputs;

        $response = $this->postJson('/api/register', $this->registerInputs);

        $response->assertJsonStructure([
            'user',
            'token'
        ]);
    }
}
