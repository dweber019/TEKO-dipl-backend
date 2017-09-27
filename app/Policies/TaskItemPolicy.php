<?php

namespace App\Policies;

use App\Models\TaskItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskItemPolicy
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
     * Determine whether the user can view the taskItem.
     *
     * @param  User  $user
     * @param  TaskItem  $taskItem
     * @return mixed
     */
    public function view(User $user, TaskItem $taskItem)
    {
        return SubjectPolicy::isTeacherOfSubject($user, $taskItem->task()->first()->lesson()->first()->subject()->first()) ||
          SubjectPolicy::isStudentOfSubject($user, $taskItem->task()->first()->lesson()->first()->subject()->first());
    }

    /**
     * Determine whether the user can update the taskItem.
     *
     * @param  User  $user
     * @param  TaskItem  $taskItem
     * @return mixed
     */
    public function update(User $user, TaskItem $taskItem)
    {
        return SubjectPolicy::isTeacherOfSubject($user, $taskItem->task()->first()->lesson()->first()->subject()->first());
    }

    /**
     * Determine whether the user can delete the taskItem.
     *
     * @param  User  $user
     * @param  TaskItem  $taskItem
     * @return mixed
     */
    public function delete(User $user, TaskItem $taskItem)
    {
        return SubjectPolicy::isTeacherOfSubject($user, $taskItem->task()->first()->lesson()->first()->subject()->first());
    }
}
