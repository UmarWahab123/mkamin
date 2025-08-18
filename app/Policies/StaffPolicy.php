<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;
use App\Models\PointOfSale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if (!$user->can('view_any_staff')) {
            return false;
        }

        return true; // Everyone with permission can see the list (we're filtering it separately)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Staff $staff): bool
    {
        if (!$user->can('view_staff')) {
            return false;
        }

        // Staff users can view their own record
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $staff->user_id === $user->id;
        }

        // POS users can only view their own staff
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $staff->point_of_sale_id === $pointOfSale->id;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_staff');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Staff $staff): bool
    {
        if (!$user->can('update_staff')) {
            return false;
        }

        // Staff users can update their own record if can_edit_profile is true
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $staff->user_id === $user->id && $staff->can_edit_profile;
        }

        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $staff->point_of_sale_id === $pointOfSale->id;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Staff $staff): bool
    {
        if (!$user->can('delete_staff')) {
            return false;
        }


        // Point of sale users can only delete staff members assigned to their POS
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

            // Check if the staff belongs to this point of sale
            if ($pointOfSale && $staff->point_of_sale_id === $pointOfSale->id) {
                return true;
            }

            return false;
        }

        // Default deny for other roles
        return true;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_staff');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Staff $staff): bool
    {
        if (!$user->can('force_delete_staff')) {
            return false;
        }

        // Same logic as delete

        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $staff->point_of_sale_id === $pointOfSale->id;
        }

        return true;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_staff');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Staff $staff): bool
    {
        if (!$user->can('restore_staff')) {
            return false;
        }

        // Same logic as delete

        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $staff->point_of_sale_id === $pointOfSale->id;
        }

        return true;
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_staff');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Staff $staff): bool
    {
        if (!$user->can('replicate_staff')) {
            return false;
        }

        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $staff->point_of_sale_id === $pointOfSale->id;
        }

        return true;
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_staff');
    }

    /**
     * Get the point of sale ID restriction for the user.
     * Returns the point_of_sale_id if the user should only see staff from a specific point of sale,
     * or null if the user can see all staff.
     */
    public function getPointOfSaleRestriction(User $user): ?int
    {

        // POS users can only view their own staff
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

            if ($pointOfSale) {
                return $pointOfSale->id;
            }
        }

        // Default to showing no staff for other roles (should not happen in practice)
        return -1; // Using -1 as a sentinel value to indicate "no access"
    }

    /**
     * Filter the staff list query based on user's permissions.
     */
    public function scopeQuery(User $user, Builder $query): Builder
    {
        // Staff users can only view themselves
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $query->where('user_id', $user->id);
        }

        // POS users can only view their own staff
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

            if ($pointOfSale) {
                return $query->where('point_of_sale_id', $pointOfSale->id);
            }
        }

        // Default to showing no staff for other roles (should not happen in practice)
        return $query;
    }
}
