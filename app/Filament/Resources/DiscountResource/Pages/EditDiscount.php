<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class EditDiscount extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = DiscountResource::class;

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
                }),
        ];
    }

    protected function authorizeAccess(): void
    {
        // Check if user can access this record
        $result = Gate::inspect('update', $this->record);

        if ($result->denied()) {
            // Show notification
            static::getResource()::handleRecordBelongsToAnotherPOS();

            // Redirect back to list
            $this->redirect(static::getResource()::getUrl());
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        // If this is a discount for fixed customers, get the template value
        if ($record->given_to === 'fixed_customers') {
            // Get the first customer's pivot data to extract the template
            $customerWithPivot = $record->customers()->first();

            if ($customerWithPivot) {
                // Get the template ID from the pivot and add it to form data
                $data['discount_card_template_id'] = $customerWithPivot->pivot->discount_card_template_id;
            }
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $data = $this->data;

        try {
            // Handle point of sales relationship
            if (isset($data['pointOfSales'])) {
                // Detach all existing point of sales
                $record->pointOfSales()->detach();

                // Attach selected point of sales
                if (!empty($data['pointOfSales'])) {
                    foreach ($data['pointOfSales'] as $posId) {
                        $record->pointOfSales()->attach($posId);
                    }
                }
            }

            // Check if given_to has been changed to any_one
            if (isset($data['given_to']) && $data['given_to'] === 'any_one') {
                // If given_to is set to any_one, detach all customers
                $record->customers()->detach();
                return;
            }

            // Only proceed with customer attachment if given_to is fixed_customers
            if (isset($data['given_to']) && $data['given_to'] === 'fixed_customers' && isset($data['customers']) && !empty($data['customers'])) {
                $templateId = $data['discount_card_template_id'] ?? null;

                // Clear existing pivot data and recreate with template ID
                $record->customers()->detach();

                // Re-attach customers with the template ID
                foreach ($data['customers'] as $customerId) {
                    $record->customers()->attach($customerId, [
                        'discount_card_template_id' => $templateId
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Error in afterSave: ' . $e->getMessage());

            // Show notification to user
            Notification::make()
                ->title('Error updating relationships')
                ->body('There was an error updating relationships for this discount: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getFooter(): View
    {
        return view('components.print-preview');
    }
}
