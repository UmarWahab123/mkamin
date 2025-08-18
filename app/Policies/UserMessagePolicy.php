<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserMessage;
use Illuminate\Auth\Access\Response;

class UserMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user::message');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserMessage $userMessage): bool
    {
        return $user->can('view_user::message');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_user::message');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserMessage $userMessage): bool
    {
        return $user->can('update_user::message');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserMessage $userMessage): bool
    {
        return $user->can('delete_user::message');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserMessage $userMessage): bool
    {
        return $user->can('restore_user::message');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserMessage $userMessage): bool
    {
        return $user->can('force_delete_user::message');
    }
}
