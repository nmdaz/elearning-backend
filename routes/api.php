<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

});

Route::prefix('auth')->group(function () {
	Route::post('login', 'Auth\AuthController@login');
	Route::post('register', 'Auth\AuthController@register');
	Route::post('password/email', 'Auth\SendPasswordResetEmailController');
	Route::post('password/reset', 'Auth\ResetPasswordController');
});



Route::get('/tokenbearer', 'TokenBearerController')
	->middleware('auth:sanctum');

Route::get('/users/{user}/courses', 'UserCourseController@index')
	->middleware('auth:sanctum', 'can:view,user');

Route::get('/users/{user}/courses/{courseId}', 'UserCourseController@show')
	->middleware('auth:sanctum', 'can:view,user');


Route::apiResource('courses', 'CourseController');

Route::middleware('auth:sanctum')->group( function () {
	Route::post('/lessons/{lesson}/comments', 'CommentController@store');

	Route::post('/comments/{comment}/like', 'LikeController@like');
	Route::post('/comments/{comment}/dislike', 'LikeController@dislike');
	Route::post('/comments/{comment}/unlike', 'LikeController@unlike');

	Route::post('/comments/{comment}/replies', 'ReplyController@store');
});



