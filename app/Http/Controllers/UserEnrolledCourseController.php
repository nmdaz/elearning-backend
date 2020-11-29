<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;

class UserEnrolledCourseController extends Controller
{
    public function index(Request $request)
    {    	
    	return new CourseCollection($request->user()->enrolledCourses);
    }

    public function store(Request $request, User $user, Course $course)
    {    	
        $request->user()->enrolledCourses()->syncWithoutDetaching($course);

    	return response()->json(['success' => 'Enrolled on Course'], 201);
    }

    public function destroy(Request $request, User $user, Course $course)
    {
        $request->user()->enrolledCourses()->detach($course);

        return response()->json(['success' => 'Course was Unenrolled'], 200);
    }
}
