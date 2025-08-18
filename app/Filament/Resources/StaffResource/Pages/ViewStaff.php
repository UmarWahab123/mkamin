<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\StaffResource\RelationManagers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ViewStaff extends ViewRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        // Get authenticated user
        /** @var User|null $user */
        $user = Auth::user();

        // Only show relation managers for super_admin and point_of_sale users
        // if (!($user && ($user->hasRole('super_admin') || $user->hasRole('point_of_sale')))) {
        //     return [];
        // }

        return [
            RelationManagers\TimeIntervalsRelationManager::class,
            RelationManagers\BookingsRelationManager::class,
        ];
    }

    protected function authorizeAccess(): void
    {
        // Check if user can access this staff
        $result = Gate::inspect('view', $this->record);

        if ($result->denied()) {
            // Show notification
            static::getResource()::handleRecordBelongsToAnotherPOS();

            // Redirect back to list
            $this->redirect(static::getResource()::getUrl());
        }
    }
}
