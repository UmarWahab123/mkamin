<?php

namespace App\Filament\Resources\ServiceCategoryResource\Pages;

use App\Filament\Resources\ServiceCategoryResource;
use App\Models\User;
use App\Policies\ServiceCategoryPolicy;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListServiceCategories extends ListRecords
{
    protected static string $resource = ServiceCategoryResource::class;

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
            $policy = new ServiceCategoryPolicy();
            $query = $policy->scopeQuery($user, $query);
        }

        return $query;
    }
}
