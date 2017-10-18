<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
     * Determine whether the user can create modelsGroups.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the modelsGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the modelsGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Check allowed add user
     *
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function addUser(User $user, Group $group)
    {
        if ($user->isTeacher()) {
            return true;
        }
    }

    /**
     * Check allowed remove user
     *
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function removeUser(User $user, Group $group)
    {
        if ($user->isTeacher()) {
            return true;
        }
    }
}
