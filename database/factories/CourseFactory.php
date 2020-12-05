<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use Illuminate\Http\UploadedFile;
use Faker\Generator as Faker;


$factory->define(Course::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});
