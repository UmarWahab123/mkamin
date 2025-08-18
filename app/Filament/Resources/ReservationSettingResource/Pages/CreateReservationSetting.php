<?php

namespace App\Filament\Resources\ReservationSettingResource\Pages;

use App\Filament\Resources\ReservationSettingResource;
use App\Filament\Traits\HasCloseAndRedirect;
use App\Models\User;
use App\Models\ReservationSetting;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CreateReservationSetting extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = ReservationSettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Use the resource's method to handle point_of_sale_id
        return ReservationSettingResource::mutateFormDataBeforeCreate($data);
    }

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();
        $date = $data['date'] ?? null;
        $pointOfSaleId = $data['point_of_sale_id'] ?? null;
        $applyToSameDays = $data['apply_to_same_days'] ?? false;

        if (!$date || !$pointOfSaleId) {
            return;
        }

        // Check if a reservation setting already exists for this date and point of sale
        $existingSetting = ReservationSettingResource::getExistingReservationSetting($date, $pointOfSaleId);

        if ($existingSetting) {
            // Create the edit URL
            $editUrl = ReservationSettingResource::getUrl('edit', ['record' => $existingSetting]);

            // Show notification with link to edit the existing setting
            Notification::make()
                ->title(__('Time Interval Already Exists'))
                ->body(__('A time interval for this date already exists.'))
                ->warning()
                ->persistent()
                ->actions([
                    Action::make('edit')
                        ->label(__('Click here to edit'))
                        ->url($editUrl),
                ])
                ->send();

            // Cancel the creation
            $this->halt();
        }
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();
        $date = $data['date'] ?? null;
        $pointOfSaleId = $data['point_of_sale_id'] ?? null;
        $applyToSameDays = $data['apply_to_same_days'] ?? false;

        if (!$applyToSameDays || !$date || !$pointOfSaleId) {
            return;
        }

        // Get the current record
        $record = $this->record;

        // Apply the same settings to future dates with the same day of week
        $this->applySettingsToFutureDays($record, $pointOfSaleId);
    }

    /**
     * Apply settings from the current record to all future dates with the same day of the week
     *
     * @param ReservationSetting $record The current reservation setting
     * @param int $pointOfSaleId The point of sale ID
     * @return void
     */
    protected function applySettingsToFutureDays(ReservationSetting $record, int $pointOfSaleId): void
    {
        $currentDate = Carbon::parse($record->date);
        $dayOfWeek = $currentDate->dayOfWeek;

        // Debug array to keep track of what's happening
        $updatedDates = [];

        // Get all future reservation settings with matching day of week for this point of sale
        $futureSettings = ReservationSetting::where('point_of_sale_id', $pointOfSaleId)
            ->where('date', '>', now()->toDateString())
            ->where('day_of_week', (string) $dayOfWeek)
            ->where('date', '!=', $record->date) // Exclude the current record
            ->get();

        // Update each matching reservation setting
        foreach ($futureSettings as $setting) {
            $setting->update([
                'opening_time' => $record->opening_time,
                'closing_time' => $record->closing_time,
                'is_closed' => $record->is_closed,
                'workers_count' => $record->workers_count,
            ]);
            $updatedDates[] = $setting->date;
        }

        // Prepare message
        $dayName = getDayName($dayOfWeek);
        $debugInfo = [];

        $debugInfo[] = sprintf("Updating all future %s reservation settings", $dayName);

        if (!empty($updatedDates)) {
            $debugInfo[] = sprintf("Updated %d settings:", count($updatedDates));
            // Sort dates for better readability
            sort($updatedDates);
            // List up to 5 updated dates
            $showDates = array_slice($updatedDates, 0, 5);
            foreach ($showDates as $date) {
                $debugInfo[] = "- {$date}";
            }
            if (count($updatedDates) > 5) {
                $debugInfo[] = "...and " . (count($updatedDates) - 5) . " more";
            }
        } else {
            $debugInfo[] = "No future settings found for {$dayName}.";
            $debugInfo[] = "Make sure you've created future reservation settings first.";
        }

        // Show a notification that settings were applied to future dates
        Notification::make()
            ->title(__('Settings Applied'))
            ->body(implode("\n", $debugInfo))
            ->success()
            ->send();
    }
}
