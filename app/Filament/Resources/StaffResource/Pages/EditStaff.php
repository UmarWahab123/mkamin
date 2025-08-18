<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use App\Filament\Traits\HasCloseAndRedirect;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EditStaff extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = StaffResource::class;

    // Store password temporarily
    protected ?string $password = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract and store password for later use with user creation/update
        $this->password = $data['password'] ?? null;

        // Remove password from staff data
        if (isset($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    // Check if user can delete this record using policy
                    if (Gate::denies('delete', $this->record)) {
                        static::getResource()::handleRecordBelongsToAnotherPOS();
                        $this->halt();
                    }

                    // Delete associated time intervals
                    $this->record->timeIntervals()->delete();
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Staff Deleted')
                        ->body('The staff member has been deleted.')
                ),
        ];
    }

    protected function authorizeAccess(): void
    {
        // Check if user can access this staff
        $result = Gate::inspect('update', $this->record);

        if ($result->denied()) {
            // Show notification
            static::getResource()::handleRecordBelongsToAnotherPOS();

            // Redirect back to list
            $this->redirect(static::getResource()::getUrl());
        }
    }

    protected function afterSave(): void
    {
        $data = $this->data;

        // Handle user account creation or update
        try {
            if ($this->record->user_id) {
                // Update existing user
                $user = User::find($this->record->user_id);

                if ($user) {
                    $updateData = [
                        'name' => $this->record->name_en,
                        'email' => $this->record->email,
                    ];

                    if ($this->password) {
                        $updateData['password'] = $this->password;
                    }

                    $user->update($updateData);

                    Log::info('Updated user account for staff #' . $this->record->id);
                } else {
                    // User ID exists but user not found - create new user
                    $this->createUserForStaff();
                }
            } elseif ($this->password) {
                // No user associated but password provided - create new user
                $this->createUserForStaff();
            }
        } catch (\Exception $e) {
            Log::error('Failed to update/create user for staff: ' . $e->getMessage());

            Notification::make()
                ->danger()
                ->title('Failed to update user account')
                ->body('Could not update the user account for this staff member: ' . $e->getMessage())
                ->send();
        }

        // Handle time intervals during updates
        if (isset($data['time_intervals']) && is_array($data['time_intervals'])) {
            // For simplicity, we'll replace all existing intervals with the new ones
            $this->record->timeIntervals()->delete();

            $existingDates = [];

            foreach ($data['time_intervals'] as $interval) {
                // Check for duplicate dates
                if (!empty($interval['date']) && in_array($interval['date'], $existingDates)) {
                    Notification::make()
                        ->title('Duplicate date detected')
                        ->body('You have duplicate time intervals for the same date. Only the last one will be saved.')
                        ->warning()
                        ->send();
                    continue;
                }

                if (!empty($interval['date'])) {
                    $existingDates[] = $interval['date'];
                }

                $this->record->timeIntervals()->create($interval);
            }
        }

        // Update time intervals with default values
        createStaffTimeIntervals($this->record->id);
    }

    private function createUserForStaff(): void
    {
        if (!$this->password) {
            return;
        }

        DB::beginTransaction();
        try {
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
            throw $e;
        }
    }
}
