<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\App;
use App\Includes\DriveStorageHelper;
use App\Http\Resources\Course as CourseResource;
use App\Http\Resources\CoursePreview as CoursePreviewResource;
use App\Http\Resources\CoursePreviewCollection;
use App\Http\Resources\CourseCollection;
use App\Course;
use GuzzleHttp\Exception\ConnectException;


class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'create']);
    }

    public function index(Request $request)
    {
        $courses;
        
    	if ((bool) $request->input('preview') == true) {
    		$courses = new CoursePreviewCollection(Course::paginate(5));
        } else {
            $courses = new CourseCollection(Course::paginate(5));
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

    public function update(Request $request, Course $course)
    {
        Gate::authorize('update', $course);

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'cover_image' => 'mimes:jpg,jpeg,bmp,png',
            'attachment' => 'mimes:zip,rar'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if (! $validated) {
            return response()->json(['errors' => ['validation' => 'Empty request data']], 422);
        }
        
        $course->update($validated);
        return response()->json(['success' => 'Course was Updated'], 200);
    }

    public function downloadAttachment(Request $request, Course $course) 
    {
        if ($course->attachment) {
            if (App::environment('production')) {

                $filename = $course->attachment;

                $dir = '/';
                $recursive = false; // Get subdirectories also?
                $contents = collect(Storage::cloud()->listContents($dir, $recursive));

                $file = $contents
                    ->where('type', '=', 'file')
                    ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                    ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
                    ->first(); // there can be duplicate file names!

                $rawData = Storage::cloud()->get($file['path']);

                return response($rawData, 200)
                        ->header('ContentType', $file['mimetype'])
                        ->header('Content-Disposition', "attachment; filename=$filename");
                
            } else {
                return Storage::disk('public')->download($course->attachment);
            }
        } else {
            return response()->json(['errors' => [
                'attachment' => 'Course has no attachment'
            ]], 404);
        }
    }
}
