<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasCloseAndRedirect;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EditCustomer extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            // Find the associated user or create one if it doesn't exist
            $user = $record->user;
            $passwordData = [];

            if (isset($data['password'])) {
                $passwordData['password'] = $data['password']; // Already hashed by form component
                // Remove password from customer data
                unset($data['password']);
            }

            if ($user) {
                // Update existing user
                $user->update([
                    'name' => $data['name_en'],
                    'email' => $data['email'],
                    ...$passwordData
                ]);
            } else {
                // Create a new user and assign to customer
                $user = User::create([
                    'name' => $data['name_en'],
                    'email' => $data['email'],
                    'password' => $passwordData['password'] ?? bcrypt('password123'), // Default password if none provided
                ]);
                $user->assignRole('customer');
                $data['user_id'] = $user->id;
            }

            // Update the customer record
            $record->update($data);

            return $record;
        });
    }
}
