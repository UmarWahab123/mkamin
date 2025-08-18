<?php

namespace App\Filament\Resources\ServiceCategoryResource\Pages;

use App\Filament\Resources\ServiceCategoryResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Gate;

class EditServiceCategory extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = ServiceCategoryResource::class;

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

    // Check authorization before record is loaded
    protected function authorizeAccess(): void
    {
        // Skip parent::authorizeAccess() to avoid Filament's built-in 403 error page

        // Check if user can access this record
        $result = Gate::inspect('update', $this->record);

        if ($result->denied()) {
            // Show notification
            static::getResource()::handleRecordBelongsToAnotherPOS();

            // Redirect back to list
            $this->redirect(static::getResource()::getUrl());
        }
    }
}
