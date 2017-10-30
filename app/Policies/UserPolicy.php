<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * Determine whether the user can view the user.
     *
     * @param  User  $user
     * @param  User  $userModel
     * @return mixed
     */
    public function view(User $user, User $userModel)
    {
        return true;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  User  $user
     * @param  User  $userModel
     * @return mixed
     */
    public function update(User $user, User $userModel)
    {
        return $this->self($user, $userModel);
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  User  $user
     * @param  User  $userModel
     * @return mixed
     */
    public function delete(User $user, User $userModel)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Is the requested user the requester
     *
     * @param User $user
     * @param User $userModel
     * @return bool
     */
    public function self(User $user, User $userModel) {
        if ($user->id === $userModel->id) {
            return true;
        }
        return false;
    }
}
