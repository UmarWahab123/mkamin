<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use App\Models\User;
use App\Policies\DiscountPolicy;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListDiscounts extends ListRecords
{
    protected static string $resource = DiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            // Apply policy-based filtering directly
            $policy = new DiscountPolicy();
            $query = $policy->scopeQuery($user, $query);
        }

        return $query;
    }
}
