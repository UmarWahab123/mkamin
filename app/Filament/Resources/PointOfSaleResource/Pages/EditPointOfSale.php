<?php

namespace App\Filament\Resources\PointOfSaleResource\Pages;

use App\Filament\Resources\PointOfSaleResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPointOfSale extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = PointOfSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Model $record) {
                    // Delete the associated user when the point of sale is deleted
                    if ($record->user) {
                        $record->user->delete();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract user data from the form
        if (isset($data['user'])) {
            $userData = $data['user'];

            // Check if email is already taken by another user
            $existingUser = \App\Models\User::where('email', $userData['email'])
                ->where('id', '!=', $this->record->user_id)
                ->first();

            if ($existingUser) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['user.email' => ['This email is already taken.']]
                );
            }

            // Add password if provided
            if (isset($data['password'])) {
                $userData['password'] = $data['password'];
            }

            // Update the user record
            $this->record->user()->update($userData);

            // Remove user data from the POS update
            unset($data['user']);
        }

        // Remove password from data
        if (isset($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load the user data for the form
        if ($this->record->user) {
            $data['user'] = [
                'name' => $this->record->user->name,
                'email' => $this->record->user->email,
            ];
        }

        return $data;
    }

}
