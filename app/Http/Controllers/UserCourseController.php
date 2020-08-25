<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseCollection;
use App\Http\Resources\Course as CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Course;

class UserCourseController extends Controller
{
    public function index(User $user)
    {        
    	$courses = $user->courses;

        return new CourseCollection($courses);
    }

    public function show(User $user, $courseId)
    {
    	$course = $user->courses()->where('courses.id', $courseId)->first();
    	return new CourseResource($course);
    }
}
