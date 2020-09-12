<?php

namespace App\Http\Controllers;

use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenBearerController extends Controller
{
    public function __invoke(Request $request)
    {
    	//return json response
    	return new User($request->user());
    }
}
