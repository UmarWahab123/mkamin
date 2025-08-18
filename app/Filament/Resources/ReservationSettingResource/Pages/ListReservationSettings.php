<?php

namespace App\Filament\Resources\ReservationSettingResource\Pages;

use App\Filament\Resources\ReservationSettingResource;
use App\Models\User;
use App\Policies\ReservationSettingPolicy;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListReservationSettings extends ListRecords
{
    protected static string $resource = ReservationSettingResource::class;

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
            $reservationSettingPolicy = new ReservationSettingPolicy();
            $query = $reservationSettingPolicy->scopeQuery($user, $query);
        }

        return $query;
    }
}
