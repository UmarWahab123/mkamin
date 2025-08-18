<?php

namespace App\Filament\Resources\ReservationSettingResource\Pages;

use App\Filament\Resources\ReservationSettingResource;
use App\Filament\Traits\HasCloseAndRedirect;
use App\Models\ReservationSetting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class EditReservationSetting extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = ReservationSettingResource::class;

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
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Time Interval Deleted')
                        ->body('The time interval has been deleted.')
                ),
        ];
    }

    protected function authorizeAccess(): void
    {
        // Check if user can access this reservation setting
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
        $data = $this->form->getState();
        $applyToSameDays = $data['apply_to_same_days'] ?? false;

        if (!$applyToSameDays) {
            // If toggle is not enabled, we don't need to apply to other days
            return;
        }

        // Apply the same settings to future dates with the same day of week
        $this->applySettingsToFutureDays($this->record);
    }

    /**
     * Apply settings from the current record to all future dates with the same day of the week
     *
     * @param ReservationSetting $record The current reservation setting
     * @return void
     */
    protected function applySettingsToFutureDays(ReservationSetting $record): void
    {
        $currentDate = Carbon::parse($record->date);
        $dayOfWeek = $currentDate->dayOfWeek;
        $pointOfSaleId = $record->point_of_sale_id;

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
