<?php

namespace App\Filament\Resources\PointOfSaleResource\Pages;

use App\Filament\Resources\PointOfSaleResource;
use App\Filament\Traits\HasCloseAndRedirect;
use App\Models\User;
use App\Models\PointOfSale;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CreatePointOfSale extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = PointOfSaleResource::class;

    // Property to store user data during creation process
    protected array $userData = [];

    protected function afterCreate(): void
    {
        createPosTimeIntervals($this->record->id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract user data from the form
        $userData = [];
        if (isset($data['user'])) {
            $userData = $data['user'];
            unset($data['user']);
        }

        // Extract and store password
        if (isset($data['password'])) {
            $userData['password'] = $data['password'];
            unset($data['password']);
        }

        // Store userData for later use in handleRecordCreation
        $this->userData = $userData;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // First create the user account
            $user = User::create($this->userData);

            // Assign point_of_sale role
            $posRole = Role::where('name', 'point_of_sale')->first();
            if ($posRole) {
                $user->assignRole($posRole);
            }

            // Check if this is the first point of sale
            $isFirst = PointOfSale::count() === 0;

            // Add user_id to POS data and set is_main_branch
            $data['user_id'] = $user->id;
            $data['is_main_branch'] = $isFirst ? 1 : 0;

            return parent::handleRecordCreation($data);
        });
    }
}
