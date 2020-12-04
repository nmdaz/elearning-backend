<?php

namespace App\Policies;

use App\User;
use App\Course;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Course $course) {
        return $user->id === $course->author_id;
    }

    public function update(User $user, Course $course) {
        return $user->id === $course->author_id;
    }

    public function delete(User $user, Course $course) {
        return $user->id === $course->author_id;
    }
}
