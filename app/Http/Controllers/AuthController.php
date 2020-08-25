<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    	$credentials = $request->only(['email', 'password']);

    	if (Auth::attempt($credentials)) {
    	    $user = Auth::user();
    	    $userResource = new UserResource($user);
    	    $token = $user->createToken($user->tokenName)->plainTextToken;

    	    return response()->json(
    	        ['token' => $token, 'user' => $userResource], 
    	        200
    	    );
    	}

    	return response()->json(['error' => 'Wrong username or password'], 422); 
    }

    public function register(Request $request, UserRepository $userRepository)
    {
    	$rules = [
		    'name' => 'required',
		    'email' => 'required|unique:users',
		    'password' => 'required|confirmed'
		];

    	$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {

			$validationError = $validator->errors();
			$errorStatusCode = 422;

		    return response()->json(
		        ['errors' => $validationError], $errorStatusCode
		    );
		}

		$user = $userRepository->create($validator->validated());
		$token = $user->createToken($user->tokenName)->plainTextToken;

        return response()->json([
        	'user' => new UserResource($user),
            'token' => $token
        ], 201);
    }

    public function logout()
    {

    }
}
