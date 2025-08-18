<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\PointOfSale;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;

class ServiceCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_service::category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ServiceCategory $serviceCategory): bool
    {
        if (!$user->can('view_service::category')) {
            return false;
        }

        // Admin can view all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only view their own service categories
        if ($user->hasRole('point_of_sale')) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $serviceCategory->point_of_sale_id === $pointOfSale->id;
        }

        // Staff users can only view their point of sale's service categories
        if ($user->hasRole('staff')) {
            return $user->staff && $serviceCategory->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (!$user->can('create_service::category')) {
            return false;
        }

        // Admin can create for any point of sale
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can create for their own point of sale
        if ($user->hasRole('point_of_sale')) {
            return PointOfSale::where('user_id', $user->id)->exists();
        }

        // Staff users can create for their point of sale
        if ($user->hasRole('staff')) {
            return $user->staff && $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ServiceCategory $serviceCategory): bool
    {
        if (!$user->can('update_service::category')) {
            return false;
        }

        // Admin can update all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only update their own service categories
        if ($user->hasRole('point_of_sale')) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $serviceCategory->point_of_sale_id === $pointOfSale->id;
        }

        // Staff users can only update their point of sale's service categories
        if ($user->hasRole('staff')) {
            return $user->staff && $serviceCategory->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ServiceCategory $serviceCategory): bool
    {
        if (!$user->can('delete_service::category')) {
            return false;
        }

        // Admin can delete all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only delete their own service categories
        if ($user->hasRole('point_of_sale')) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $serviceCategory->point_of_sale_id === $pointOfSale->id;
        }

        // Staff users can only delete their point of sale's service categories
        if ($user->hasRole('staff')) {
            return $user->staff && $serviceCategory->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_service::category');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ServiceCategory $serviceCategory): bool
    {
        if (!$user->can('force_delete_service::category')) {
            return false;
        }

        // Admin can force delete all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only force delete their own service categories
        if ($user->hasRole('point_of_sale')) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $serviceCategory->point_of_sale_id === $pointOfSale->id;
        }

        // Staff users can only force delete their point of sale's service categories
        if ($user->hasRole('staff')) {
            return $user->staff && $serviceCategory->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_service::category');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ServiceCategory $serviceCategory): bool
    {
        if (!$user->can('restore_service::category')) {
            return false;
        }

        // Admin can restore all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only restore their own service categories
        if ($user->hasRole('point_of_sale')) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $serviceCategory->point_of_sale_id === $pointOfSale->id;
        }

        // Staff users can only restore their point of sale's service categories
        if ($user->hasRole('staff')) {
            return $user->staff && $serviceCategory->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_service::category');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ServiceCategory $serviceCategory): bool
    {
        if (!$user->can('replicate_service::category')) {
            return false;
        }

        // Admin can replicate all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only replicate their own service categories
        if ($user->hasRole('point_of_sale')) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $serviceCategory->point_of_sale_id === $pointOfSale->id;
        }

        // Staff users can only replicate their point of sale's service categories
        if ($user->hasRole('staff')) {
            return $user->staff && $serviceCategory->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_service::category');
    }

    /**
     * Filter the query based on the user's permissions.
     */
    public function scopeQuery(User $user, Builder $query): Builder
    {
        // Admin can view all service categories
        if ($user->hasRole(['super_admin', 'admin'])) {
            return $query;
        }

        // POS users can only view their own service categories
        if ($user->hasRole('point_of_sale')) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            if ($pointOfSale) {
                return $query->where('point_of_sale_id', $pointOfSale->id);
            }
        }

        // Staff users can only view their point of sale's service categories
        if ($user->hasRole('staff') && $user->staff) {
            return $query->where('point_of_sale_id', $user->staff->point_of_sale_id);
        }

        // Default deny for other roles
        return $query->whereRaw('1=0');
    }
}
