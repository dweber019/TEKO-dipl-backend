<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        return SubjectPolicy::isTeacherOfSubject($user, $task->lesson()->first()->subject()->first()) ||
          SubjectPolicy::isStudentOfSubject($user, $task->lesson()->first()->subject()->first());
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
        return SubjectPolicy::isTeacherOfSubject($user, $task->lesson()->first()->subject()->first());
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
        return SubjectPolicy::isTeacherOfSubject($user, $task->lesson()->first()->subject()->first());
    }

    public function isTeacher(User $user, Task $task) {
        return SubjectPolicy::isTeacherOfSubject($user, $task->lesson()->first()->subject()->first());
    }
}
