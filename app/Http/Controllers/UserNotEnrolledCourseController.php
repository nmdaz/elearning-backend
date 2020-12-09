<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;

class UserNotEnrolledCourseController extends Controller
{
    public function index(Request $request, User $user) 
    {
    	$userId = $user->id;

    	$courses =  Course::whereNotIn('id', function($query) use ($userId) {
    	    $query->select('course_id')->from('course_user')->where('user_id', '=', $userId);
    	})
    	->where('published', '=', false)
    	->where('author_id', '!=', $userId)
    	->paginate(5);

    	return new CourseCollection($courses);
    }
}
