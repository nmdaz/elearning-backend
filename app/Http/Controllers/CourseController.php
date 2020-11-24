<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Course as CourseResource;
use App\Http\Resources\CoursePreview as CoursePreviewResource;
use App\Http\Resources\CoursePreviewCollection;
use App\Http\Resources\CourseCollection;
use App\Course;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store');
    }

    public function index(Request $request)
    {
        $courses;
        
    	if ((bool) $request->input('preview') == true) {
    		$courses = new CoursePreviewCollection(Course::paginate(2));
        } else {
            $courses = new CourseCollection(Course::paginate(2));
        }

    	return $courses; 
    }

    public function show(Request $request, Course $course)
    {
        if ((bool) $request->input('preview') == true) {
            return new CoursePreviewResource($course);
        } else {
            return new CourseResource($course);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'cover_image' => 'required|mimes:jpg,jpeg,bmp,png',
            'attachment' => 'mimes:zip,rar'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course = new Course();
        $course->author_id = $request->user()->id;
        $course->name = $request->name;
        $course->description = $request->description;
        $course->cover_image = $request->cover_image;

        if ($request->attachment) {
            $course->attachment = $request->attachment;
        }

        $course->save();
        
        return response()->json([
            'success' => 'Course Created',
            'course_id' => $course->id
        ], 201);
    }

    public function downloadAttachment(Request $request, Course $course) {
        return Storage::disk('public')->download($course->attachment);
    }
}
