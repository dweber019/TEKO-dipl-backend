<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

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
          $this->isStudent($user, $lesson);
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

    /**
     * Is the user the teacher of subject
     *
     * @param User $user
     * @param Lesson $lesson
     * @return bool
     */
    public function isTeacher(User $user, Lesson $lesson) {
        return SubjectPolicy::isTeacherOfSubject($user, $lesson->subject()->first());
    }

    /**
     * Is the user a student of the subject
     *
     * @param User $user
     * @param Lesson $lesson
     * @return bool
     */
    public function isStudent(User $user, Lesson $lesson) {
        return !!DB::table('lessons')
          ->join('subjects', 'subjects.id', '=', 'lessons.subject_id')
          ->join('subject_user', 'subject_user.subject_id', '=', 'subjects.id')
          ->where([
            ['lessons.id', '=', $lesson->id],
            ['subject_user.user_id', '=', $user->id],
          ])
          ->count();
    }
}
