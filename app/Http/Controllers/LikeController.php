<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function like(Request $request, Comment $comment) {
    	$comment->likeBy($request->user());

    	return response()->json(['message' => 'Success'], 200);
    }

    public function dislike(Request $request, Comment $comment) {
    	$comment->dislikeBy($request->user());

    	return response()->json(['message' => 'Success'], 200);
    }

    public function unlike(Request $request, Comment $comment) {
    	$comment->unlikeBy($request->user());

    	return response()->json(['message' => 'Success'], 200);
    }
}
