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

        // for ($a = 0; $a < 7; $a++) {
        //     $course1 = factory(Course::class)->create([
        //         'author' => $user->id
        //     ]);

        //     $section1 = factory(Section::class)->create([
        //         'course_id' => $course1->id
        //     ]);
        //     $lesson1 = factory(Lesson::class)->create([
        //         'section_id' => $section1->id
        //     ]);

        //     $comment = factory(Comment::class)->create([
        //         'user_id' => $user->id,
        //         'lesson_id' => $lesson1->id
        //     ]);

        //     $lesson11 = factory(Lesson::class)->create([
        //         'section_id' => $section1->id
        //     ]);
        //     $section1->lessons()->save($lesson1);
        //     $section1->lessons()->save($lesson11);

        //     $course1->sections()->save($section1);

        //     $section2 = factory(Section::class)->create([
        //         'course_id' => $course1->id
        //     ]);
        //     $lesson2 = factory(Lesson::class)->create([
        //         'section_id' => $section2->id
        //     ]);
        //     $lesson22 = factory(Lesson::class)->create([
        //         'section_id' => $section2->id
        //     ]);
        //     $section2->lessons()->save($lesson2);
        //     $section2->lessons()->save($lesson2);

        //     $course1->sections()->save($section2);
        // }
    }
}
