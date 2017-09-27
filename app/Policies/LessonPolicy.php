<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the lesson.
     *
     * @param  User  $user
     * @param  Lesson  $lesson
     * @return mixed
     */
    public function view(User $user, Lesson $lesson)
    {
        return SubjectPolicy::isTeacherOfSubject($user, $lesson->subject()->first()) ||
          SubjectPolicy::isStudentOfSubject($user, $lesson->subject()->first());
    }

    /**
     * Determine whether the user can update the lesson.
     *
     * @param  User  $user
     * @param  Lesson  $lesson
     * @return mixed
     */
    public function update(User $user, Lesson $lesson)
    {
        return SubjectPolicy::isTeacherOfSubject($user, $lesson->subject()->first());
    }

    /**
     * Determine whether the user can delete the lesson.
     *
     * @param  User  $user
     * @param  Lesson  $lesson
     * @return mixed
     */
    public function delete(User $user, Lesson $lesson)
    {
        return SubjectPolicy::isTeacherOfSubject($user, $lesson->subject()->first());
    }

    public function isTeacher(User $user, Lesson $lesson) {
        return SubjectPolicy::isTeacherOfSubject($user, $lesson->subject()->first());
    }
}
