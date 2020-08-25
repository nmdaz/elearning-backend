<?php

namespace App\Repositories;

use App\User;

class UserRepository implements UserRepositoryInterface {

	public function make($attribute) {
		return User::make($attribute);
	}

	//create and save to database
	public function create($attribute) {
		return User::create($attribute);
	}

}