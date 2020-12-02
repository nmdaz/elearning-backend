<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Course;
use App\Section;
use App\Lesson;
use App\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user1 = factory(User::class)->create([
            'email' => 'email01@gmail.com',
            'password' => 'pass1234'
        ]);

        $user1->authoredCourses()->saveMany(factory(Course::class, 3)->make());

        $user1->authoredCourses->each(function($course) {
            $course->sections()->saveMany(factory(Section::class, 3)->make());
            
            $course->sections->each(function($section) {
                $section->lessons()->saveMany(factory(Lesson::class, 5)->make());
            });
        });


        $user2 = factory(User::class)->create([
            'email' => 'email02@gmail.com',
            'password' => 'pass1234'
        ]);

        $user2->authoredCourses()->saveMany(factory(Course::class, 3)->make());

        $user2->authoredCourses->each(function($course) {
            $course->sections()->saveMany(factory(Section::class, 3)->make());
            
            $course->sections->each(function($section) {
                $section->lessons()->saveMany(factory(Lesson::class, 5)->make());
            });
        });
    }
}
