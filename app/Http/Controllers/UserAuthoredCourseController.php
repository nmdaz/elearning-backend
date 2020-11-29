<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;

class UserAuthoredCourseController extends Controller
{
    public function index(Request $request)
    {
    	return new CourseCollection($request->user()->authoredCourses);
    }
}
