<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use App\Filament\Traits\HasCloseAndRedirect;
use App\Models\Staff;
use App\Models\TimeInterval;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateStaff extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = StaffResource::class;

    // Store password temporarily
    protected ?string $password = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract and store password for later use with user creation
        $this->password = $data['password'] ?? null;

        // Remove password from staff data
        if (isset($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Create user for this staff member
        if (isset($this->password) && $this->password) {
            try {
                DB::beginTransaction();

                $user = User::create([
                    'name' => $this->record->name_en,
                    'email' => $this->record->email,
                    'password' => $this->password,
                ]);

                // Assign staff role
                $user->assignRole('staff');

                // Update staff with user_id
                $this->record->update(['user_id' => $user->id]);

                DB::commit();

                Notification::make()
                    ->success()
                    ->title('Staff account created')
                    ->body('A user account has been created for this staff member.')
                    ->send();

                Log::info('Created user account for staff #' . $this->record->id);
            } catch (\Exception $e) {
                DB::rollBack();

                Notification::make()
                    ->danger()
                    ->title('Failed to create user account')
                    ->body('Could not create a user account for this staff member: ' . $e->getMessage())
                    ->send();

                Log::error('Failed to create user for staff: ' . $e->getMessage());
            }
        }

        // Create default time intervals
        createStaffTimeIntervals($this->record->id);
    }
}
