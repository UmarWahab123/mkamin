<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductAndService;
use App\Models\PointOfSale;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;

class ProductAndServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_product::and::service');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductAndService $productAndService): bool
    {
        if (!$user->can('view_product::and::service')) {
            return false;
        }

        // Admin can view all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only view their own products and services
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $productAndService->point_of_sale_id === $pointOfSale->id;
        }

        // Staff can only view products and services from their assigned POS
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $productAndService->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_product::and::service');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductAndService $productAndService): bool
    {
        if (!$user->can('update_product::and::service')) {
            return false;
        }

        // Admin can update all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only update their own products and services
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $productAndService->point_of_sale_id === $pointOfSale->id;
        }

        // Staff can only update products and services from their assigned POS
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $productAndService->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductAndService $productAndService): bool
    {
        if (!$user->can('delete_product::and::service')) {
            return false;
        }

        // Admin can delete all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only delete their own products and services
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $productAndService->point_of_sale_id === $pointOfSale->id;
        }

        // Staff can only delete products and services from their assigned POS
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $productAndService->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_product::and::service');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ProductAndService $productAndService): bool
    {
        if (!$user->can('force_delete_product::and::service')) {
            return false;
        }

        // Admin can force delete all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only force delete their own products and services
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $productAndService->point_of_sale_id === $pointOfSale->id;
        }

        // Staff can only force delete products and services from their assigned POS
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $productAndService->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_product::and::service');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ProductAndService $productAndService): bool
    {
        if (!$user->can('restore_product::and::service')) {
            return false;
        }

        // Admin can restore all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only restore their own products and services
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $productAndService->point_of_sale_id === $pointOfSale->id;
        }

        // Staff can only restore products and services from their assigned POS
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $productAndService->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_product::and::service');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ProductAndService $productAndService): bool
    {
        if (!$user->can('replicate_product::and::service')) {
            return false;
        }

        // Admin can replicate all
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // POS users can only replicate their own products and services
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            return $pointOfSale && $productAndService->point_of_sale_id === $pointOfSale->id;
        }

        // Staff can only replicate products and services from their assigned POS
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $productAndService->point_of_sale_id === $user->staff->point_of_sale_id;
        }

        return false;
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_product::and::service');
    }

    /**
     * Filter the query based on the user's permissions.
     */
    public function scopeQuery(User $user, Builder $query): Builder
    {
        // Admin can view all products and services
        if ($user->hasRole(['super_admin', 'admin'])) {
            return $query;
        }

        // POS users can only view their own products and services
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

            if ($pointOfSale) {
                return $query->where('point_of_sale_id', $pointOfSale->id);
            }
        }

        // Staff can only view products and services from their assigned POS
        if ($user->roles()->where('name', 'staff')->exists()) {
            return $query->where('point_of_sale_id', $user->staff->point_of_sale_id);
        }

        // Default deny for other roles
        return $query->whereRaw('1=0');
    }
}
