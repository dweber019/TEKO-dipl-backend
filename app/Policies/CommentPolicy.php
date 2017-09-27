<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
     * Determine whether the user can update the comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return $this->commentOwnedByUser($user, $comment);
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return $this->commentOwnedByUser($user, $comment);
    }

    private function commentOwnedByUser(User $user, Comment $comment) {
        if ($comment->user_id === $user->id) {
            return true;
        }
        return false;
    }
}
