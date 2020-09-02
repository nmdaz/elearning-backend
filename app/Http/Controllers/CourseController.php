<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Course as CourseResource;
use App\Http\Resources\CourseCollection;
use App\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
    	if ((bool) $request->input('preview') === true) 
    		CourseResource::$includeRelations = false;

    	$courses = new CourseCollection(Course::all());

    	return $courses; 
    }

    public function show(Request $request, Course $course)
    {
    	return new CourseResource($course);
    }
}
