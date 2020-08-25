<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
	Route::post('login', 'AuthController@login');
	Route::post('register', 'AuthController@register');
});

Route::get('/tokenbearer', 'TokenBearerController')
	->middleware('auth:sanctum');

Route::get('/users/{user}/courses', 'UserCourseController@index')
	->middleware('auth:sanctum', 'can:view,user');

Route::get('/users/{user}/courses/{courseId}', 'UserCourseController@show')
	->middleware('auth:sanctum', 'can:view,user');

Route::post('/lessons/{lesson}/comments', 'CommentController@store')
	->middleware('auth:sanctum');;
