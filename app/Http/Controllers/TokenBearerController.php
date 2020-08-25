<?php

namespace App\Http\Controllers;

use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenBearerController extends Controller
{
    public function __invoke(Request $request)
    {
    	//get current authenticated user
    	$user = Auth::user();

    	//return json response
    	return new User($user);
    }
}
