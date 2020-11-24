<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lesson;
use Faker\Generator as Faker;

$factory->define(Lesson::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'video_url' => 'http://youtu.be/dQw4w9WgXcQ'
    ];
});
