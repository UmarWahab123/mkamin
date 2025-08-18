<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasCloseAndRedirect;

class EditUser extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label(__('Delete')),
        ];
    }


    protected function afterSave(): void
    {
        $user = $this->record;
        $roleIds = $this->data['roles'] ?? [];

        // Handle the roles assignment
        if (!empty($roleIds)) {
            // Clear existing roles first to avoid duplicates
            $user->roles()->detach();

            // Attach the new roles
            $user->roles()->attach($roleIds);

            Notification::make()
                ->title(__('User updated successfully with assigned role'))
                ->success()
                ->send();
        }
    }
}
