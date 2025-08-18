<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Discount;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PointOfSale;

class DiscountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isPointOfSale()) {
            return true;
        }

        return $user->can('view_any_discount');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Discount $discount): bool
    {
        if ($user->isPointOfSale()) {
            return $discount->pointOfSales->contains($user->pointOfSale);
        }

        return $user->can('view_discount');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isPointOfSale()) {
            return true;
        }

        return $user->can('create_discount');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Discount $discount): bool
    {
        if ($user->isPointOfSale()) {
            return $discount->pointOfSales->contains($user->pointOfSale);
        }

        return $user->can('update_discount');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Discount $discount): bool
    {
        if ($user->isPointOfSale()) {
            return $discount->pointOfSales->contains($user->pointOfSale);
        }

        return $user->can('delete_discount');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        if ($user->isPointOfSale()) {
            return true;
        }

        return $user->can('delete_any_discount');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Discount $discount): bool
    {
        if ($user->isPointOfSale()) {
            return $discount->pointOfSales->contains($user->pointOfSale);
        }

        return $user->can('force_delete_discount');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        if ($user->isPointOfSale()) {
            return true;
        }

        return $user->can('force_delete_any_discount');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Discount $discount): bool
    {
        if ($user->isPointOfSale()) {
            return $discount->pointOfSales->contains($user->pointOfSale);
        }

        return $user->can('restore_discount');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        if ($user->isPointOfSale()) {
            return true;
        }

        return $user->can('restore_any_discount');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Discount $discount): bool
    {
        if ($user->isPointOfSale()) {
            return $discount->pointOfSales->contains($user->pointOfSale);
        }

        return $user->can('replicate_discount');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        if ($user->isPointOfSale()) {
            return true;
        }

        return $user->can('reorder_discount');
    }

    /**
     * Filter the query based on the user's permissions.
     */
    public function scopeQuery(User $user, Builder $query): Builder
    {
        // Admin can view all discounts
        if ($user->hasRole(['super_admin', 'admin'])) {
            return $query;
        }

        // POS users can only view discounts that belong to their POS
        if ($user->roles()->where('name', 'point_of_sale')->exists()) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

            if ($pointOfSale) {
                return $query->whereHas('pointOfSales', function ($query) use ($pointOfSale) {
                    $query->where('discount_point_of_sale.point_of_sale_id', $pointOfSale->id);
                });
            }
        }

        // Default deny for other roles
        return $query->whereRaw('1=0');
    }
}
