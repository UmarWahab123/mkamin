<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasCloseAndRedirect;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CreateCustomer extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = CustomerResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Create a new user with the given email and password
            $user = User::create([
                'name' => $data['name_'.app()->getLocale()],
                'email' => $data['email'],
                'password' => $data['password'], // This is already hashed by the form component
            ]);

            // Assign the customer role to the user
            $user->assignRole('customer');

            // Remove password from customer data
            unset($data['password']);

            // Set the user_id for the customer
            $data['user_id'] = $user->id;

            // Create the customer record
            return static::getModel()::create($data);
        });
    }
}
