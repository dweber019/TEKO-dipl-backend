<?php

namespace App\Policies;

use App\Models\TaskItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

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
        return $this->isTeacher($user, $taskItem) ||
          $this->isStudent($user, $taskItem);
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
        return $this->isTeacher($user, $taskItem);
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
        return $this->isTeacher($user, $taskItem);
    }

    public function isTeacher(User $user, TaskItem $taskItem) {
        return $user->isTeacher() && !!DB::table('task_items')
            ->join('tasks', 'tasks.id', '=', 'task_items.task_id')
            ->join('lessons', 'lessons.id', '=', 'tasks.lesson_id')
            ->join('subjects', 'subjects.id', '=', 'lessons.subject_id')
            ->where([
              ['task_items.id', '=', $taskItem->id],
              ['subjects.teacher_id', '=', $user->id],
            ])
            ->count();
    }

    public function isStudent(User $user, TaskItem $taskItem) {
        return !!DB::table('task_items')
          ->join('tasks', 'tasks.id', '=', 'task_items.task_id')
          ->join('lessons', 'lessons.id', '=', 'tasks.lesson_id')
          ->join('subjects', 'subjects.id', '=', 'lessons.subject_id')
          ->join('subject_user', 'subject_user.subject_id', '=', 'subjects.id')
          ->where([
            ['task_items.id', '=', $taskItem->id],
            ['subject_user.user_id', '=', $user->id],
          ])
          ->count();
    }
}
