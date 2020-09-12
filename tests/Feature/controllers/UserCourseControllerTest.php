<?php

namespace Tests\Feature\controllers;

use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserCourseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void {
        parent::setUp();

        Storage::fake('public');;

        $this->seed();

        $this->withoutExceptionHandling();
    }

    public function test_guest_get_user_courses_route_return_Authentication_Exception()
    {
        $this->expectException(AuthenticationException::class);

        $this->get('/api/users/1/courses');
    }

    public function test_user_access_others_users_courses_return_Authorization_Exception()
    {
        $this->expectException(AuthorizationException::class);

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        Sanctum::actingAs($user1);

        $this->get("/api/users/$user2->id/courses");
    }

    public function test_user_get_all_courses_return_200()
    {
        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $this->get("/api/users/$user->id/courses")
            ->assertStatus(200);
    }

    public function test_user_get_courses_return_json_courses()
    {
        $user = User::first();

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/$user->id/courses");

        $this->assertNotNUll($response['courses']);

        $this->assertEquals(
            $user->courses->count(), 
            count($response['courses'])
        );

    }

    public function test_user_get_course_return_json_course_with_status_200()
    {
        $user = User::first();

        Sanctum::actingAs($user);

        $course = $user->courses[0];
        $courseId = $course->id;

        $coverImageType = Storage::disk('public')->getMimetype($course->cover_image);
        $coverImage = base64_encode(Storage::disk('public')->get($course->cover_image));

        $response = $this->getJson("/api/users/$user->id/courses/$courseId")
            ->assertOk()
            ->assertJsonStructure([
                'course' => [
                    'id',
                    'user_id',
                    'name',
                    'description',
                    'cover_image',
                    'cover_image_mime_type',
                    'created_at',
                    'updated_at',
                    'sections' => [
                        0 => [
                            'id',
                            'course_id',
                            'name',
                            'description',
                            'created_at',
                            'updated_at',
                            'lessons' => [
                                0 => [
                                    'id',
                                    'section_id',
                                    'name',
                                    'description',
                                    'video',
                                    'created_at',
                                    'updated_at'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
