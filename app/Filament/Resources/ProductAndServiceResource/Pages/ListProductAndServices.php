<?php

namespace App\Filament\Resources\ProductAndServiceResource\Pages;

use App\Filament\Resources\ProductAndServiceResource;
use App\Models\User;
use App\Policies\ProductAndServicePolicy;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAndService;

class ListProductAndServices extends ListRecords
{
    protected static string $resource = ProductAndServiceResource::class;

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
            $policy = new ProductAndServicePolicy();
            $query = $policy->scopeQuery($user, $query);
        }

        return $query;
    }

    public function getTabs(): array
    {
        return [
            'all' => ListRecords\Tab::make(__('All'))
                ->badge(ProductAndService::query()->count()),
            'salon_only' => ListRecords\Tab::make(__('Salon Only'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('can_be_done_at_salon', true)->where('can_be_done_at_home', false))
                ->badge(ProductAndService::query()->where('can_be_done_at_salon', true)->where('can_be_done_at_home', false)->count()),
            'home_only' => ListRecords\Tab::make(__('Home Only'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('can_be_done_at_home', true)->where('can_be_done_at_salon', false))
                ->badge(ProductAndService::query()->where('can_be_done_at_home', true)->where('can_be_done_at_salon', false)->count()),
            'both' => ListRecords\Tab::make(__('Both'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('can_be_done_at_salon', true)->where('can_be_done_at_home', true))
                ->badge(ProductAndService::query()->where('can_be_done_at_salon', true)->where('can_be_done_at_home', true)->count()),
        ];
    }
}
