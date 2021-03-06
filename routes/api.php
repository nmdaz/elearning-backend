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
Route::apiResource('courses.sections', 'SectionController');
Route::apiResource('courses.sections.lessons', 'LessonController');

Route::get('courses/{course}/download-attachment', 'CourseController@downloadAttachment');

Route::get('/lessons/{lesson}/comments', 'CommentController@index');

Route::middleware('auth:sanctum')->group( function () {
	Route::get('/me', 'TokenBearerController');

	Route::post('courses/{course}/remove-attachment', 'CourseController@removeAttachment');

	Route::post('courses/{course}/publish', 'CourseController@publish');
	Route::post('courses/{course}/unpublish', 'CourseController@unpublish');

	Route::post('/lessons/{lesson}/comments', 'CommentController@store');
	Route::post('/comments/{comment}/like', 'LikeController@like');
	Route::post('/comments/{comment}/dislike', 'LikeController@dislike');
	Route::post('/comments/{comment}/unlike', 'LikeController@unlike');
	Route::post('/comments/{comment}/replies', 'ReplyController@store');

	Route::get('/users/{user}/authored-courses', 'UserAuthoredCourseController@index');
	Route::get('/users/{user}/not-enrolled-courses', 'UserNotEnrolledCourseController@index');
	Route::get('/users/{user}/enrolled-courses', 'UserEnrolledCourseController@index');
	Route::post('/users/{user}/enrolled-courses/{course}', 'UserEnrolledCourseController@store');
	Route::delete('/users/{user}/enrolled-courses/{course}', 'UserEnrolledCourseController@destroy');
});







