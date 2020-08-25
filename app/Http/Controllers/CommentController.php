<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;


class CommentController extends Controller
{
    public function store(Request $request, Lesson $lesson) {
    	$userId = Auth::user()->id;
    	$lessonId = $lesson->id;

    	$comment = new Comment();
    	$comment->user_id = $userId;
    	$comment->lesson_id = $lesson->id;
    	$comment->body = $request->body;
    	$comment->save();

    	return response()->json(['message' => 'Comment was successfully submitted'], 201);
    }
}
