<?php

namespace App\Http\Controllers;

use App\Course;
use App\Lesson;
use App\Section;
use App\Http\Resources\Lesson as LessonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:sanctum')->only('store');
    }

    public function show(Request $request, Course $course, Section $section, Lesson $lesson)
    {
    	return new LessonResource($lesson);
    }

    public function store(Request $request, Course $course, Section $section)
    {
    	$validator = Validator::make($request->all(), [
    		'name' => 'required|string',
    		'video_url' => 'required|url'
    	]);

    	if ($validator->fails()) {
    		return response()->json(['errors' => $validator->errors()], 422);
    	}

    	$section->lessons()->create($validator->validated());

    	return response()->json(['success' => 'Lesson successfully created'], 201);
    }
}
