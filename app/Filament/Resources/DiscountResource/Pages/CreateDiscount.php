<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use App\Models\Discount;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;
use App\Filament\Traits\HasCloseAndRedirect;

class CreateDiscount extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = DiscountResource::class;

    protected function authorizeAccess(): void
    {
        // Check if user can create discounts
        $result = Gate::inspect('create', Discount::class);

        if ($result->denied()) {
            // Show notification
            static::getResource()::handleRecordBelongsToAnotherPOS();

            // Redirect back to list
            $this->redirect(static::getResource()::getUrl());
        }
    }

    // Prevent Filament from automatically handling relationships
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove relationships data to prevent automatic handling
        // We'll handle them manually in afterCreate
        if (isset($data['customers'])) {
            unset($data['customers']);
        }

        if (isset($data['pointOfSales'])) {
            unset($data['pointOfSales']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        try {
            // First, detach any automatically created relationships to prevent duplicates
            $record->pointOfSales()->detach();
            $record->customers()->detach();

            // Attach point of sales to the discount
            if (isset($data['pointOfSales']) && !empty($data['pointOfSales'])) {
                foreach ($data['pointOfSales'] as $posId) {
                    $record->pointOfSales()->attach($posId);
                }
            }

            // Check if fixed_customers is selected and customers exist
            if ($record->given_to === 'fixed_customers' && isset($data['customers']) && !empty($data['customers'])) {
                $templateId = $data['discount_card_template_id'] ?? null;

                // Store the template ID in the pivot table for each customer
                foreach ($data['customers'] as $customerId) {
                    $record->customers()->attach($customerId, [
                        'discount_card_template_id' => $templateId
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Error in afterCreate: ' . $e->getMessage());

            // Show notification to user
            Notification::make()
                ->title('Error attaching relationships')
                ->body('There was an error creating relationships for this discount: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
