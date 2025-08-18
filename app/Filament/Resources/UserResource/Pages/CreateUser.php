<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
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
                ->title(__('User created successfully with assigned role'))
                ->success()
                ->send();
        }
    }
}
