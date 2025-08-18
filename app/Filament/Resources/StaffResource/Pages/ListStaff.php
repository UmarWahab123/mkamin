<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use App\Models\Staff;
use App\Models\User;
use App\Policies\StaffPolicy;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListStaff extends ListRecords
{
    protected static string $resource = StaffResource::class;

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
            $staffPolicy = new StaffPolicy();
            $query = $staffPolicy->scopeQuery($user, $query);
        }

        return $query;
    }
}
