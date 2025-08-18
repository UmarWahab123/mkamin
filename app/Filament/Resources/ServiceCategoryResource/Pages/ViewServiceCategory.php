<?php

namespace App\Filament\Resources\ServiceCategoryResource\Pages;

use App\Filament\Resources\ServiceCategoryResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;
use Filament\Notifications\Notification;

class ViewServiceCategory extends ViewRecord
{
    protected static string $resource = ServiceCategoryResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if (Gate::denies('view', $this->record)) {
            $this->handleRecordBelongsToAnotherPOS();
            $this->halt();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make()
                ->label(__('Edit'))
                ->before(function () {
                    if (Gate::denies('update', $this->record)) {
                        $this->handleRecordBelongsToAnotherPOS();
                        $this->halt();
                    }
                }),
            \Filament\Actions\DeleteAction::make()
                ->label(__('Delete'))
                ->before(function () {
                    if (Gate::denies('delete', $this->record)) {
                        $this->handleRecordBelongsToAnotherPOS();
                        $this->halt();
                    }
                }),
        ];
    }

    protected function handleRecordBelongsToAnotherPOS(): void
    {
        Notification::make()
            ->title(__('Access Denied'))
            ->body(__('You do not have permission to access this service category.'))
            ->danger()
            ->persistent()
            ->send();

        $this->redirectTo(ServiceCategoryResource::getUrl('index'));
    }
}
