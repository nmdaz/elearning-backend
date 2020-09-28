<?php

namespace App\Http\Controllers;

use App\Course;
use App\Section;
use App\Http\Resources\Section as SectionResource;
use App\Http\Resources\SectionCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:sanctum')->only('store');
	}

    public function index(Request $request, Course $course)
    {
        return new SectionCollection($course->sections);
    }

    public function store(Request $request, Course $course)
    {
    	$validator = Validator::make($request->all(), [
    		'name' => 'required|string'
    	]);

    	if ($validator->fails()) {
    		return response()->json(['errors' => $validator->errors()], 422);
    	}

        $section = $course->sections()->create($validator->validated());

    	return response()->json([
            'success' => 'Section was successfully created',
            'section_id' => $section->id
        ], 201);
    }

    public function show(Request $request, Course $course, Section $section)
    {
    	return new SectionResource($section);
    }

    public function destroy(Request $request, Course $course, Section $section)
    {
        $section->delete();
        return response()->json([
            'success' => 'Section was successfully deleted'
        ], 201);
    }
}
