<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReservationSetting;
use App\Models\PointOfSale;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;

class ReservationSettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_reservation::setting');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReservationSetting $reservationSetting): bool
    {
        if (!$user->can('view_reservation::setting')) {
            return false;
        }

        // Admin can view all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only view their own reservation settings
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $reservationSetting->point_of_sale_id === $pointOfSale->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_reservation::setting');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReservationSetting $reservationSetting): bool
    {
        if (!$user->can('update_reservation::setting')) {
            return false;
        }

        // Admin can update all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only update their own reservation settings
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $reservationSetting->point_of_sale_id === $pointOfSale->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReservationSetting $reservationSetting): bool
    {
        if (!$user->can('delete_reservation::setting')) {
            return false;
        }

        // Admin can delete all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only delete their own reservation settings
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $reservationSetting->point_of_sale_id === $pointOfSale->id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_reservation::setting');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ReservationSetting $reservationSetting): bool
    {
        if (!$user->can('force_delete_reservation::setting')) {
            return false;
        }

        // Admin can force delete all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only force delete their own reservation settings
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $reservationSetting->point_of_sale_id === $pointOfSale->id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_reservation::setting');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ReservationSetting $reservationSetting): bool
    {
        if (!$user->can('restore_reservation::setting')) {
            return false;
        }

        // Admin can restore all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only restore their own reservation settings
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $reservationSetting->point_of_sale_id === $pointOfSale->id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_reservation::setting');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ReservationSetting $reservationSetting): bool
    {
        if (!$user->can('replicate_reservation::setting')) {
            return false;
        }

        // Admin can replicate all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only replicate their own reservation settings
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $reservationSetting->point_of_sale_id === $pointOfSale->id;
        }

        return false;
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_reservation::setting');
    }

    /**
     * Filter the query based on the user's permissions.
     */
    public function scopeQuery(User $user, Builder $query): Builder
    {
        // Admin can view all reservation settings
        if ($user->hasRole(['super_admin', 'admin'])) {
            return $query;
        }

        // POS users can only view their own reservation settings
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

            if ($pointOfSale) {
                return $query->where('point_of_sale_id', $pointOfSale->id);
            }
        }

        // Default deny for other roles
        return $query->whereRaw('1=0');
    }
}
