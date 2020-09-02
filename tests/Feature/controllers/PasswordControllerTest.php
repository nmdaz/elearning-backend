<?php

namespace Tests\Feature\controllers;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
	use RefreshDatabase;

	public function setUp() :void {
		parent::setUp();

		$this->seed();

		$this->withoutExceptionHandling();

		$this->withHeaders(['Accept' => 'application/json']);
	}

	public function test_send_password_reset_with_empty_email_return_validationn_error()
	{
		$user = User::first();

		Notification::fake();

		$this->expectException(ValidationException::class);

		$this->postjson('/api/auth/password/email')
            ->assertStatus(422);

        Notification::assertNothingSent();		
	}

    public function test_send_password_reset_email()
    {
    	$user = User::first();

        Notification::fake();
        
        $this->postjson('/api/auth/password/email', ['email' => $user->email])
            ->assertOk();

        Notification::assertSentTo([$user], ResetPassword::class);       
    }

    public function test_reset_password_with_empty_data_return_validation_error()
    {
    	$this->expectException(ValidationException::class);
    	$response = $this->postjson('/api/auth/password/reset');
    }

    public function test_reset_password()
    {
		$user = User::first();

	    Notification::fake();
	    
	    $this->postjson('/api/auth/password/email', ['email' => $user->email])
	        ->assertOk();

	     $data = [
	     	'email' => $user->email,
	     	'token' => '',
	     	'password' => 'password0000',
	     	'password_confirmation' => 'password0000',
	     ];

	    Notification::assertSentTo(
	        $user,
	        ResetPassword::class,
	        function ($notification, $channels) use (&$data) {
	        	$data['token'] = $notification->token;
	        	return true;
	        }
	    );

	    $this->postjson('/api/auth/password/reset', $data)
	    	->assertOk();

	    $this->postjson('/api/auth/login', $data)
	    	->assertOk();

    }
}
