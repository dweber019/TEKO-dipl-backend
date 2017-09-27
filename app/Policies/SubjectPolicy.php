<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
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
     * Determine whether the user can view the subject.
     *
     * @param  User  $user
     * @param  Subject $subject
     * @return mixed
     */
    public function view(User $user, Subject $subject)
    {
        if (
            ($user->isTeacher() && $subject->teacher_id === $user->id) ||
            !!$user->subjects()->where('subject_id', $subject->id)->count()
        ) {
            return true;
        }
    }

    /**
     * Determine whether the user can create subjects.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->isTeacher()) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the subject.
     *
     * @param  User  $user
     * @param  Subject  $subject
     * @return mixed
     */
    public function update(User $user, Subject $subject)
    {
        if ($user->isTeacher() && $subject->teacher_id === $user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the subject.
     *
     * @param  User  $user
     * @param  Subject  $subject
     * @return mixed
     */
    public function delete(User $user, Subject $subject)
    {
        if ($user->isTeacher() && $subject->teacher_id === $user->id) {
            return true;
        }
    }

    public function isTeacher(User $user, Subject $subject)
    {
        if (
          ($user->isTeacher() && $subject->teacher_id === $user->id)
        ) {
            return true;
        }
    }

    public static function isTeacherOfSubject(User $user, Subject $subject) {
        if ($user->isTeacher() && $subject->teacher_id === $user->id) {
            return true;
        }
        return false;
    }

    public static function isStudentOfSubject(User $user, Subject $subject) {
        return !!$user->subjects()->where('subject_id', $subject->id)->count();
    }
}
