<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
	Route::post('login', 'Auth\AuthController@login');
	Route::post('register', 'Auth\AuthController@register');
	Route::post('password/email', 'Auth\SendPasswordResetEmailController');
	Route::post('password/reset', 'Auth\ResetPasswordController');
});

Route::apiResource('courses', 'CourseController');

Route::middleware('auth:sanctum')->group( function () {
	Route::get('/me', 'TokenBearerController');

	Route::post('/lessons/{lesson}/comments', 'CommentController@store');

	Route::post('/comments/{comment}/like', 'LikeController@like');
	Route::post('/comments/{comment}/dislike', 'LikeController@dislike');
	Route::post('/comments/{comment}/unlike', 'LikeController@unlike');

	Route::post('/comments/{comment}/replies', 'ReplyController@store');

	Route::middleware(['can:view,user'])->group( function () {
		Route::get('/users/{user}/courses', 'UserCourseController@index');
		Route::get('/users/{user}/courses/{courseId}', 'UserCourseController@show');
	});
});







