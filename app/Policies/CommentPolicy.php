<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any comments.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the comment.
     *
     * @param User $user
     * @param Comment $comment
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can create comments.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the comment.
     *
     * @param User $user
     * @param Comment $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return (int)$comment->user_id === (int)$user->id;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param User $user
     * @param Comment $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return $comment->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the comment.
     *
     * @param User $user
     * @param Comment $comment
     * @return mixed
     */
    public function restore(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the comment.
     *
     * @param User $user
     * @param Comment $comment
     * @return mixed
     */
    public function forceDelete(User $user, Comment $comment)
    {
        //
    }
}
