<?php

namespace App\Repositories;

interface UserRepositoryInterface {
	//create but dont save
	public function make($attribute);

	//create and save
	public function create($attribute);
}