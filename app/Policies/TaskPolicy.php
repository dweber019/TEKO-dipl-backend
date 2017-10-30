<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class TaskPolicy
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
     * Determine whether the user can view the task.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return mixed
     */
    public function view(User $user, Task $task)
    {
        return $this->isTeacher($user, $task) ||
          $this->isStudent($user, $task);
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        return $this->isTeacher($user, $task);
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        return $this->isTeacher($user, $task);
    }

    /**
     * Is the user the teacher of the task
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function isTeacher(User $user, Task $task) {
        return $user->isTeacher() && !!DB::table('tasks')
          ->join('lessons', 'lessons.id', '=', 'tasks.lesson_id')
          ->join('subjects', 'subjects.id', '=', 'lessons.subject_id')
          ->where([
            ['tasks.id', '=', $task->id],
            ['subjects.teacher_id', '=', $user->id],
          ])
          ->count();
    }

    /**
     * Is the user a student of the task
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function isStudent(User $user, Task $task) {
        return TaskPolicy::isUserTeacher($user, $task);
    }

    /**
     * Is the user the teacher of the related subject
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public static function isUserTeacher(User $user, Task $task)
    {
        return !!DB::table('tasks')
          ->join('lessons', 'lessons.id', '=', 'tasks.lesson_id')
          ->join('subjects', 'subjects.id', '=', 'lessons.subject_id')
          ->join('subject_user', 'subject_user.subject_id', '=', 'subjects.id')
          ->where([
            ['tasks.id', '=', $task->id],
            ['subject_user.user_id', '=', $user->id],
          ])
          ->count();
    }
}
